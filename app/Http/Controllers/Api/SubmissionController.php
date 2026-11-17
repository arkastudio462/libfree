<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Submission;
use App\Support\BookPresentation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    /**
     * License options accepted by the upload form.
     */
    private const LICENSES = [
        'domain-public',
        'cc-by',
        'cc-by-sa',
        'cc-by-nc',
        'cc-by-nd',
        'other',
    ];

    /**
     * The authenticated reader's own submissions.
     */
    public function index(Request $request): JsonResponse
    {
        $submissions = $request->user()
            ->submissions()
            ->with(['category:id,name,slug', 'book:id,title,slug,is_published'])
            ->latest()
            ->get()
            ->map(fn (Submission $submission) => $submission->toPayload());

        return response()->json(['data' => $submissions]);
    }

    /**
     * Submit an openly licensed manuscript and its files for review.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author_name' => ['required', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1000', 'max:'.(now()->year + 1)],
            'category' => ['required', 'string', Rule::exists('categories', 'slug')],
            'source_url' => ['required', 'string', 'url', 'max:2048'],
            'license_type' => ['required', Rule::in(self::LICENSES)],
            'notes' => ['nullable', 'string', 'max:2000'],
            'manuscript' => ['required', 'file', 'extensions:pdf,epub', 'max:51200'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $category = Category::query()->where('slug', $data['category'])->firstOrFail();
        $directory = 'submissions/'.Str::uuid();

        $manuscript = $request->file('manuscript');
        $manuscriptPath = $manuscript->store($directory, 'local');
        $cover = $request->file('cover');
        $coverPath = $cover?->store($directory.'/cover', 'local');

        $submission = $request->user()->submissions()->create([
            'category_id' => $category->id,
            'title' => $data['title'],
            'author_name' => $data['author_name'],
            'year' => $data['year'] ?? null,
            'source_url' => $data['source_url'],
            'license_type' => $data['license_type'],
            'notes' => $data['notes'] ?? null,
            'manuscript_path' => $manuscriptPath,
            'manuscript_name' => $manuscript->getClientOriginalName(),
            'manuscript_size' => $manuscript->getSize(),
            'manuscript_mime' => $manuscript->getMimeType(),
            'manuscript_hash' => hash_file('sha256', $manuscript->getRealPath()),
            'cover_path' => $coverPath,
            'cover_name' => $cover?->getClientOriginalName(),
            'cover_mime' => $cover?->getMimeType(),
            'status' => Submission::STATUS_PENDING,
        ]);

        $submission->setRelation('user', $request->user());
        $submission->load(['category:id,name,slug', 'book:id,title,slug,is_published']);

        return response()->json(['data' => $submission->toPayload()], 201);
    }

    /**
     * Stream an uploaded file to its owner or an administrator.
     */
    public function file(Request $request, Submission $submission, string $type): StreamedResponse
    {
        abort_unless(
            $request->user()->is_admin || $submission->user_id === $request->user()->id,
            403
        );

        abort_unless(in_array($type, ['naskah', 'sampul'], true), 404);

        $path = $type === 'sampul' ? $submission->cover_path : $submission->manuscript_path;
        $name = $type === 'sampul' ? $submission->cover_name : $submission->manuscript_name;

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path, $name ?: basename($path));
    }

    /**
     * Moderation queue for administrators.
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $status = $request->query('status');

        $query = Submission::query()
            ->with(['user:id,name,email', 'category:id,name,slug', 'book:id,title,slug,is_published'])
            ->latest();

        if (in_array($status, [Submission::STATUS_PENDING, Submission::STATUS_APPROVED, Submission::STATUS_REJECTED], true)) {
            $query->where('status', $status);
        }

        $submissions = $query->get();

        return response()->json([
            'data' => $submissions->map(fn (Submission $submission) => $submission->toPayload()),
            'meta' => [
                'pending' => Submission::query()->where('status', Submission::STATUS_PENDING)->count(),
                'approved' => Submission::query()->where('status', Submission::STATUS_APPROVED)->count(),
                'rejected' => Submission::query()->where('status', Submission::STATUS_REJECTED)->count(),
                'total' => Submission::query()->count(),
            ],
        ]);
    }

    /**
     * Approve or reject a submission.
     *
     * Approval promotes the submission into an unpublished draft book with the
     * uploaded files attached. A curator still has to clear the license audit
     * before the book can appear in the public catalog.
     */
    public function review(Request $request, Submission $submission): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([Submission::STATUS_APPROVED, Submission::STATUS_REJECTED])],
            'reviewer_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $book = DB::transaction(function () use ($request, $submission, $data) {
            $book = null;

            if ($data['status'] === Submission::STATUS_APPROVED) {
                $book = $submission->book_id
                    ? Book::query()->find($submission->book_id)
                    : $this->promoteToDraft($submission);
            }

            $submission->update([
                'status' => $data['status'],
                'reviewer_note' => $data['reviewer_note'] ?? null,
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'book_id' => $book?->id,
            ]);

            return $book;
        });

        $submission->load(['user:id,name,email', 'category:id,name,slug', 'book:id,title,slug,is_published']);

        return response()->json(['data' => $submission->toPayload()]);
    }

    /**
     * Create an unpublished book from an approved submission.
     */
    private function promoteToDraft(Submission $submission): Book
    {
        $category = $submission->category ?? Category::query()->orderBy('id')->firstOrFail();
        $author = $this->resolveAuthor($submission->author_name);
        $slug = $this->uniqueSlug($submission->title);
        $format = $submission->manuscript_path
            ? strtolower(pathinfo($submission->manuscript_path, PATHINFO_EXTENSION))
            : 'pdf';
        $formats = [$format];
        $sizes = [$format => $this->formatBytes($submission->manuscript_size)];
        $synopsis = $submission->notes
            ?: $submission->title.' — naskah yang diusulkan oleh pembaca dan sedang dilengkapi kurator.';

        $licenseStatus = match ($submission->license_type) {
            'domain-public' => 'public_domain',
            'cc-by', 'cc-by-sa', 'cc-by-nc', 'cc-by-nd' => 'cc',
            default => 'unverified',
        };

        return Book::query()->create([
            'title' => $submission->title,
            'slug' => $slug,
            'author_id' => $author->id,
            'category_id' => $category->id,
            'year' => $submission->year,
            'pages' => null,
            'synopsis' => $synopsis,
            'quote' => null,
            'quote_source' => null,
            'quote_source_role' => null,
            'rating' => 0,
            'reviews_count' => 0,
            'reads_label' => null,
            'bookmark_count' => 0,
            'rating_dist' => null,
            'formats' => $formats,
            'sizes' => $sizes,
            'card' => BookPresentation::card($submission->title, $formats, $author, $category->slug),
            'showcase' => null,
            'detail' => null,
            'reader_content' => BookPresentation::readerPreview($submission->title, $synopsis),
            'manuscript_path' => $submission->manuscript_path,
            'manuscript_name' => $submission->manuscript_name,
            'manuscript_size' => $submission->manuscript_size,
            'manuscript_mime' => $submission->manuscript_mime,
            'manuscript_hash' => $submission->manuscript_hash,
            'cover_path' => $submission->cover_path,
            'cover_mime' => $submission->cover_mime,
            'download_count' => 0,
            'license_status' => $licenseStatus,
            'license_name' => $this->licenseName($submission->license_type),
            'license_url' => $licenseStatus === 'public_domain'
                ? 'https://creativecommons.org/publicdomain/mark/1.0/'
                : null,
            'source_url' => $submission->source_url,
            'audit_notes' => 'Dipromosikan dari unggahan #'.$submission->id.'. Menunggu audit lisensi akhir sebelum tayang.',
            'is_published' => false,
            'published_at' => null,
            'audited_at' => null,
            'popularity' => null,
            'is_featured' => false,
            'featured_rank' => null,
            'is_trending' => false,
            'trend_rank' => null,
            'rank' => null,
            'related_order' => null,
        ]);
    }

    private function resolveAuthor(string $name): Author
    {
        $slug = Str::slug($name) ?: 'penulis-anonim';
        $initials = collect(preg_split('/\s+/', trim($name)) ?: [])
            ->filter()
            ->take(3)
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');

        return Author::query()->firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'short_name' => null,
                'initials' => $initials ?: 'AN',
                'avatar_class' => null,
                'line' => null,
                'badge' => null,
                'bio' => null,
                'subtitle' => null,
                'photo_url' => null,
                'favorite_rank' => null,
            ]
        );
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'naskah';
        $slug = $base;
        $suffix = 2;

        while (Book::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function formatBytes(?int $bytes): string
    {
        if (! $bytes) {
            return '—';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = min((int) floor(log($bytes, 1024)), count($units) - 1);

        return round($bytes / (1024 ** $power), 1).' '.$units[$power];
    }

    private function licenseName(string $licenseType): string
    {
        return match ($licenseType) {
            'domain-public' => 'Domain Publik',
            'cc-by' => 'Creative Commons BY',
            'cc-by-sa' => 'Creative Commons BY-SA',
            'cc-by-nc' => 'Creative Commons BY-NC',
            'cc-by-nd' => 'Creative Commons BY-ND',
            default => 'Lisensi terbuka lainnya',
        };
    }
}

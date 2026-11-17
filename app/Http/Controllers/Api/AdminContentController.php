<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Support\BookPresentation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminContentController extends Controller
{
    /**
     * License states the curator can assign to a book.
     */
    private const LICENSE_STATUSES = [
        'public_domain',
        'cc',
        'permission',
        'unverified',
        'restricted',
    ];

    /**
     * The auditable license states that may be published publicly.
     */
    private const PUBLISHABLE_STATUSES = [
        'public_domain',
        'cc',
        'permission',
    ];

    /**
     * Content audit queue for administrators.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Book::query()
            ->with(['author:id,name', 'category:id,name'])
            ->latest('id');

        $status = $request->query('status');

        match ($status) {
            'published' => $query->where('is_published', true),
            'draft' => $query->where('is_published', false),
            'unverified' => $query->where('license_status', 'unverified'),
            default => null,
        };

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function (Builder $q) use ($search) {
                $like = '%'.$search.'%';
                $q->where('title', 'like', $like)
                    ->orWhereHas('author', fn (Builder $author) => $author->where('name', 'like', $like));
            });
        }

        $books = $query->paginate(min(max((int) $request->query('per_page', 20), 1), 100));

        return response()->json([
            'data' => $books->getCollection()
                ->map(fn (Book $book) => $this->payload($book))
                ->values(),
            'meta' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
                'published' => Book::query()->where('is_published', true)->count(),
                'draft' => Book::query()->where('is_published', false)->count(),
                'unverified' => Book::query()->where('license_status', 'unverified')->count(),
            ],
        ]);
    }

    /**
     * Update audit metadata and publication state for one book.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $data = $request->validate([
            'license_status' => ['sometimes', Rule::in(self::LICENSE_STATUSES)],
            'license_name' => ['nullable', 'string', 'max:255'],
            'license_url' => ['nullable', 'url', 'max:2048'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'audit_notes' => ['nullable', 'string', 'max:5000'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $licenseStatus = $data['license_status'] ?? $book->license_status;
        $sourceUrl = $data['source_url'] ?? $book->source_url;
        $publish = $data['is_published'] ?? $book->is_published;

        if ($publish && ! in_array($licenseStatus, self::PUBLISHABLE_STATUSES, true)) {
            throw ValidationException::withMessages([
                'license_status' => 'Buku hanya dapat dipublikasikan setelah lisensi diverifikasi (domain publik, Creative Commons, atau izin pemegang hak).',
            ]);
        }

        if ($publish && blank($sourceUrl)) {
            throw ValidationException::withMessages([
                'source_url' => 'URL sumber naskah wajib diisi sebelum buku dipublikasikan.',
            ]);
        }

        $book->license_status = $licenseStatus;
        $book->license_url = array_key_exists('license_url', $data) ? $data['license_url'] : $book->license_url;
        $book->source_url = $sourceUrl;
        $book->audit_notes = array_key_exists('audit_notes', $data) ? $data['audit_notes'] : $book->audit_notes;
        $book->is_published = (bool) $publish;
        $book->audited_at = now();

        if (array_key_exists('license_name', $data)) {
            $book->license_name = $data['license_name'];
        }

        if ($publish) {
            $book->published_at ??= now();
        } else {
            $book->published_at = null;
        }

        $book->save();
        $book->load(['author:id,name', 'category:id,name']);

        return response()->json(['data' => $this->payload($book)]);
    }

    /**
     * Attach or replace the manuscript and cover files for a book.
     */
    public function upload(Request $request, Book $book): JsonResponse
    {
        $data = $request->validate([
            'manuscript' => ['nullable', 'file', 'extensions:pdf,epub', 'max:51200'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if (! $request->hasFile('manuscript') && ! $request->hasFile('cover')) {
            throw ValidationException::withMessages([
                'manuscript' => 'Pilih berkas naskah (PDF/EPUB) atau sampul untuk diunggah.',
            ]);
        }

        $book->load(['author', 'category']);
        $directory = 'books/'.$book->id.'/'.Str::uuid();

        if ($manuscript = $request->file('manuscript')) {
            $format = strtolower($manuscript->getClientOriginalExtension() ?: 'pdf');
            $path = $manuscript->store($directory, 'local');
            $size = $manuscript->getSize();

            $book->manuscript_path = $path;
            $book->manuscript_name = $manuscript->getClientOriginalName();
            $book->manuscript_size = $size;
            $book->manuscript_mime = $manuscript->getMimeType();
            $book->manuscript_hash = hash_file('sha256', $manuscript->getRealPath());
            $book->formats = [$format];
            $book->sizes = [$format => $this->formatBytes($size)];

            if ($book->author && $book->category) {
                $book->card = BookPresentation::card(
                    $book->title,
                    [$format],
                    $book->author,
                    $book->category->slug
                );
            }
        }

        if ($cover = $request->file('cover')) {
            $book->cover_path = $cover->store($directory.'/cover', 'local');
            $book->cover_mime = $cover->getMimeType();
        }

        $book->save();
        $book->load(['author:id,name', 'category:id,name']);

        return response()->json(['data' => $this->payload($book)]);
    }

    /**
     * Preview the current cover for administrators.
     */
    public function cover(Book $book): StreamedResponse
    {
        abort_unless($book->cover_path && Storage::disk('local')->exists($book->cover_path), 404);

        return Storage::disk('local')->response(
            $book->cover_path,
            'sampul-'.$book->slug,
            ['Content-Type' => $book->cover_mime ?: 'image/jpeg']
        );
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

    /**
     * Shape one audit row for the admin SPA.
     */
    private function payload(Book $book): array
    {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'slug' => $book->slug,
            'year' => $book->year,
            'author' => $book->author?->name,
            'category' => $book->category?->name,
            'license_status' => $book->license_status,
            'license_name' => $book->license_name,
            'license_url' => $book->license_url,
            'source_url' => $book->source_url,
            'audit_notes' => $book->audit_notes,
            'is_published' => $book->is_published,
            'has_manuscript' => filled($book->manuscript_path),
            'manuscript_name' => $book->manuscript_name,
            'manuscript_size' => $book->manuscript_size,
            'manuscript_format' => $book->manuscriptFormat(),
            'has_cover' => filled($book->cover_path),
            'cover_url' => $book->cover_path ? url('/api/admin/konten/'.$book->id.'/sampul') : null,
            'download_count' => $book->download_count,
            'published_at' => $book->published_at?->toIso8601String(),
            'audited_at' => $book->audited_at?->toIso8601String(),
            'updated_at' => $book->updated_at?->toIso8601String(),
        ];
    }
}

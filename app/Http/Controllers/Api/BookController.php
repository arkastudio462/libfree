<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Bookmark;
use App\Models\Review;
use App\Models\ReviewHelpful;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * List books with search, filters, sorting, and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 12), 1), 100);

        $books = Book::query()
            ->published()
            ->with(['author:id,name,slug,short_name,initials,avatar_class,line,badge,bio,subtitle,photo_url,favorite_rank', 'category:id,name,slug,icon'])
            ->categorySlugs($request->query('kategori'))
            ->formats($request->query('format'))
            ->when($request->query('min_rating'), fn (Builder $query, mixed $rating) => $query->where('rating', '>=', (float) $rating))
            ->search($request->query('q'))
            ->sort($request->query('sort'))
            ->paginate($perPage)
            ->withQueryString();

        $bookmarkedIds = $request->user()
            ? Bookmark::query()
                ->where('user_id', $request->user()->id)
                ->whereIn('book_id', $books->getCollection()->pluck('id'))
                ->pluck('book_id')
                ->all()
            : [];

        return response()->json([
            'data' => $books->getCollection()
                ->map(fn (Book $book) => $this->listResource($book, in_array($book->id, $bookmarkedIds, true)))
                ->values(),
            'meta' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
            ],
        ]);
    }

    /**
     * Single book detail with related books and reviews.
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $book = Book::query()
            ->published()
            ->with([
                'author:id,name,slug,short_name,initials,avatar_class,line,badge,bio,subtitle,photo_url,favorite_rank',
                'category:id,name,slug,icon',
                'reviews',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $isBookmarked = $request->user()
            ? Bookmark::query()->where('user_id', $request->user()->id)->where('book_id', $book->id)->exists()
            : false;

        $viewerId = $request->user()?->id;

        $helpfulReviewIds = $viewerId
            ? ReviewHelpful::query()
                ->where('user_id', $viewerId)
                ->whereIn('review_id', $book->reviews->modelKeys())
                ->pluck('review_id')
                ->all()
            : [];

        $related = Book::query()
            ->published()
            ->with(['author:id,name,slug,short_name', 'category:id,name,slug'])
            ->where('category_id', $book->category_id)
            ->whereKeyNot($book->id)
            ->orderByRaw('COALESCE(related_order, 9999)')
            ->orderBy('id')
            ->limit(4)
            ->get()
            ->map(fn (Book $item) => $this->listResource($item))
            ->values();

        return response()->json([
            'data' => array_merge($this->listResource($book, $isBookmarked), [
                'sizes' => $book->sizes,
                'quote' => $book->quote,
                'quote_source' => $book->quote_source,
                'quote_source_role' => $book->quote_source_role,
                'detail' => $this->normalizeDetail($book),
                'license' => $this->licenseResource($book),
                'related' => $related,
                'reviews' => $book->reviews
                    ->sortByDesc(fn (Review $review) => $review->user_id !== null)
                    ->values()
                    ->map(fn (Review $review) => $review->toPublicArray(
                        $viewerId,
                        in_array($review->id, $helpfulReviewIds, true),
                    ))
                    ->values(),
            ]),
        ]);
    }

    /**
     * Shape one book for list/related payloads.
     */
    private function listResource(Book $book, bool $isBookmarked = false): array
    {
        return $book->toCatalogArray($isBookmarked);
    }

    /**
     * Merge stored presentation JSON with derived fallbacks so every book
     * has renderable detail sections (tabs, specs, metrics, downloads...).
     */
    private function normalizeDetail(Book $book): array
    {
        $fallback = $this->fallbackDetail($book);
        $stored = $book->detail ?? [];

        // Distribution is partially stored (bars/satisfaction); keep the
        // derived rating/based_on from the fallback.
        if (isset($stored['distribution'])) {
            $stored['distribution'] = array_merge($fallback['distribution'], $stored['distribution']);
        }

        return array_merge($fallback, $stored);
    }

    private function fallbackDetail(Book $book): array
    {
        $author = $book->author;
        $category = $book->category;
        $card = $book->card ?? [];
        $sizes = $book->sizes ?? [];
        $hours = $book->pages ? round($book->pages / 60, 1) : null;
        $licenseLabel = $book->licenseLabel();

        $downloads = [];
        if ($book->manuscript_path) {
            $format = $book->manuscriptFormat() ?: 'pdf';
            $downloads[] = [
                'label' => Str::upper($format).' ('.($sizes[$format] ?? '—').')',
                'class' => $format === 'pdf' ? 'bg-brand-coral-light hover:bg-[#fad3d3]' : 'bg-brand-blue-light hover:bg-[#d6eefb]',
                'icon' => $format === 'pdf' ? 'picture_as_pdf' : 'download',
                'url' => url('/api/buku/'.$book->slug.'/unduh'),
            ];
        }

        $distribution = $this->fallbackDistribution($book);
        [$coverTitle, $coverAccent] = $this->splitTitle($book->title);
        $sourceHost = $book->source_url ? parse_url($book->source_url, PHP_URL_HOST) : null;

        return [
            'banner' => null,
            'cover' => [
                'bg' => '#FAFAF7',
                'image' => $book->cover_path ? url('/api/buku/'.$book->slug.'/sampul') : null,
                'series_badge' => $card['spine_kicker'] ?? $category?->name,
                'genre' => $card['badge'] ?? $category?->name,
                'title' => $coverTitle,
                'title_accent' => $coverAccent,
                'author' => $author?->name,
                'footer_left' => 'Arsip Terbuka libfree',
                'footer_right' => $book->hasVerifiedLicense() ? 'Lisensi Terverifikasi' : 'Lisensi Ditinjau',
            ],
            'cover_badges' => [
                ['label' => $licenseLabel, 'icon' => 'lock_open', 'class' => 'bg-brand-green/20', 'icon_class' => 'text-brand-green', 'fill' => true],
            ],
            'category_badges' => array_values(array_filter([
                $category ? ['label' => $category->name, 'class' => 'bg-brand-coral'] : null,
                ['label' => 'Naskah Domain Publik', 'class' => 'bg-brand-green-light'],
                $book->year ? ['label' => 'Edisi '.$book->year, 'class' => 'bg-brand-blue-light'] : null,
            ])),
            'subtitle' => null,
            'metrics' => [
                ['icon' => 'star', 'value' => (string) $book->rating, 'label' => 'Nilai kurasi', 'icon_class' => 'text-brand-yellow', 'fill' => true],
                ['icon' => 'auto_stories', 'value' => $book->reads_label ?? '—', 'label' => 'Kali Dibaca', 'icon_class' => 'text-brand-blue', 'fill' => false],
                ['icon' => 'timer', 'value' => $hours !== null ? $hours.' Jam' : '—', 'label' => ($book->pages ?: '—').' Halaman', 'icon_class' => 'text-brand-coral', 'fill' => false],
                ['icon' => 'translate', 'value' => 'ID/EYD', 'label' => '+ Glosarium', 'icon_class' => 'text-brand-green', 'fill' => false],
            ],
            'tabs' => array_values(array_filter([
                ['label' => 'Sinopsis Lengkap', 'html' => '<p>'.$book->synopsis.'</p>'],
                $book->audit_notes ? [
                    'label' => 'Catatan Kurator Arsip',
                    'html' => '<p>'.e($book->audit_notes).'</p><div class="p-3 bg-brand-lavender rounded-xl border-2 border-brand-dark text-xs space-y-1"><span class="font-bold block text-brand-dark">Status audit konten</span><p>'.e($licenseLabel).($book->audited_at ? ' • diaudit '.e($book->audited_at->translatedFormat('j F Y')) : '').'</p></div>',
                ] : null,
                [
                    'label' => 'Sumber & Lisensi',
                    'html' => '<p>Karya ini disajikan sebagai arsip terbuka dengan status lisensi <strong class="font-extrabold text-brand-dark">'.e($licenseLabel).'</strong>.</p>'.($book->source_url ? '<p>Sumber naskah: <a class="underline decoration-brand-yellow decoration-2 font-bold" href="'.e($book->source_url).'" rel="noopener noreferrer" target="_blank">'.e($sourceHost ?: $book->source_url).'</a></p>' : '').($book->license_url ? '<p>Rujukan lisensi: <a class="underline decoration-brand-yellow decoration-2 font-bold" href="'.e($book->license_url).'" rel="noopener noreferrer" target="_blank">'.e(parse_url($book->license_url, PHP_URL_HOST) ?: $book->license_url).'</a></p>' : ''),
                ],
            ])),
            'license' => $this->licenseResource($book),
            'specs' => [
                ['label' => 'Tahun Terbit Asli', 'value' => $book->year ? (string) $book->year : '—'],
                ['label' => 'Penerbit Digital', 'value' => 'Arsip Terbuka libfree'],
                ['label' => 'ID Katalog Arsip', 'value' => 'LIB-'.($book->year ?? 'NA').'-'.strtoupper(substr(str_replace('-', '', $book->slug), 0, 8))],
                ['label' => 'Lisensi Karya', 'value' => $licenseLabel],
                ['label' => 'Sumber Naskah', 'value' => $sourceHost ?: '—'],
            ],
            'downloads' => $downloads,
            'extra_formats' => null,
            'quote_label' => null,
            'quote_count' => null,
            'distribution' => $distribution,
            'series' => null,
            'related_heading' => [
                'title' => 'Koleksi '.($category?->name ?? 'Buku').' Terkait',
                'subtitle' => 'Rekomendasi kurator untuk penjelajahan lebih jauh',
            ],
            'reviews_heading' => [
                'title' => 'Ulasan & Diskusi Komunitas',
                'subtitle' => $book->reviews_count > 0
                    ? number_format($book->reviews_count, 0, ',', '.').' pembaca telah membagikan catatan pemikiran'
                    : 'Belum ada ulasan; jadilah pembaca pertama yang berbagi catatan.',
            ],
        ];
    }

    /**
     * Public license payload for the detail page.
     */
    private function licenseResource(Book $book): array
    {
        return [
            'status' => $book->license_status,
            'name' => $book->licenseLabel(),
            'url' => $book->license_url,
            'source_url' => $book->source_url,
            'verified' => $book->hasVerifiedLicense(),
            'audited_at' => $book->audited_at?->toDateString(),
        ];
    }

    /**
     * Split a title into a short cover headline and an accent line.
     *
     * @return array{0: string, 1: string|null}
     */
    private function splitTitle(string $title): array
    {
        $words = preg_split('/\s+/', trim($title)) ?: [];
        $first = (string) array_shift($words);
        $rest = implode(' ', $words);

        return [Str::upper($first), $rest !== '' ? Str::upper($rest) : null];
    }

    private function fallbackDistribution(Book $book): array
    {
        $dist = $book->rating_dist;
        $bars = [];

        if (is_array($dist) && $dist !== []) {
            foreach (array_values($dist) as $index => $width) {
                $stars = 5 - $index;
                $width = (int) $width;
                $bars[] = [
                    'stars' => $stars,
                    'width' => $width,
                    'label' => $width <= 1 ? '<1%' : $width.'%',
                ];
            }
        } else {
            // Deterministic split derived from the rating (demo distribution).
            $p5 = (int) round(($book->rating - 4) * 80 + 40);
            $rest = 100 - $p5;
            $p4 = (int) round($rest * 0.6);
            $p3 = (int) round($rest * 0.25);
            $p2 = (int) round($rest * 0.1);
            $p1 = max(0, 100 - $p5 - $p4 - $p3 - $p2);

            foreach ([$p5, $p4, $p3, $p2, $p1] as $index => $width) {
                $bars[] = [
                    'stars' => 5 - $index,
                    'width' => $width,
                    'label' => $width <= 1 ? '<1%' : $width.'%',
                ];
            }
        }

        return [
            'rating' => $book->rating,
            'based_on' => $book->reviews_count > 0
                ? 'Berdasarkan '.number_format($book->reviews_count, 0, ',', '.').' ulasan pembaca'
                : 'Berdasarkan penilaian kurator',
            'bars' => $bars,
            'satisfaction' => $book->reviews_count > 0
                ? ($bars[0]['width'] ?? 0) + ($bars[1]['width'] ?? 0).'% Pembaca Puas'
                : 'Belum ada ulasan pembaca',
        ];
    }
}

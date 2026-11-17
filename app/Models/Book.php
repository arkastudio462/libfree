<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'author_id',
        'category_id',
        'year',
        'pages',
        'synopsis',
        'quote',
        'quote_source',
        'quote_source_role',
        'rating',
        'reviews_count',
        'reads_label',
        'bookmark_count',
        'rating_dist',
        'formats',
        'sizes',
        'card',
        'showcase',
        'detail',
        'reader_content',
        'manuscript_path',
        'manuscript_name',
        'manuscript_size',
        'manuscript_mime',
        'manuscript_hash',
        'cover_path',
        'cover_mime',
        'download_count',
        'license_status',
        'license_name',
        'license_url',
        'source_url',
        'audit_notes',
        'is_published',
        'published_at',
        'audited_at',
        'popularity',
        'is_featured',
        'featured_rank',
        'is_trending',
        'trend_rank',
        'rank',
        'related_order',
    ];

    protected function casts(): array
    {
        return [
            'rating_dist' => 'array',
            'formats' => 'array',
            'sizes' => 'array',
            'card' => 'array',
            'showcase' => 'array',
            'detail' => 'array',
            'reader_content' => 'array',
            'rating' => 'float',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'audited_at' => 'datetime',
            'manuscript_size' => 'integer',
            'download_count' => 'integer',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Only books cleared by the content audit may appear in public queries.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Human-readable license label for badges and spec sheets.
     */
    public function licenseLabel(): string
    {
        return $this->license_name ?: match ($this->license_status) {
            'public_domain' => 'Domain Publik',
            'cc' => 'Lisensi Creative Commons',
            'permission' => 'Izin penerbit / pemegang hak',
            default => 'Lisensi belum diverifikasi',
        };
    }

    /**
     * Whether the license metadata is strong enough for public display.
     */
    public function hasVerifiedLicense(): bool
    {
        return in_array($this->license_status, ['public_domain', 'cc', 'permission'], true)
            && filled($this->source_url);
    }

    /**
     * Detected manuscript format (epub/pdf/mobi) from the stored file.
     */
    public function manuscriptFormat(): ?string
    {
        if (! $this->manuscript_path) {
            return null;
        }

        return strtolower(pathinfo($this->manuscript_path, PATHINFO_EXTENSION)) ?: null;
    }

    /**
     * Shape one book for catalog/list payloads.
     */
    public function toCatalogArray(bool $isBookmarked = false): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'year' => $this->year,
            'pages' => $this->pages,
            'synopsis' => $this->synopsis,
            'rating' => $this->rating,
            'reviews_count' => $this->reviews_count,
            'reads_label' => $this->reads_label,
            'bookmark_count' => $this->bookmark_count,
            'is_bookmarked' => $isBookmarked,
            'reader_available' => ! empty($this->reader_content)
                || in_array($this->manuscriptFormat(), ['pdf', 'epub'], true),
            'rating_dist' => $this->rating_dist,
            'formats' => $this->formats,
            'popularity' => $this->popularity,
            'rank' => $this->rank,
            'is_featured' => $this->is_featured,
            'featured_rank' => $this->featured_rank,
            'is_trending' => $this->is_trending,
            'trend_rank' => $this->trend_rank,
            'related_order' => $this->related_order,
            'author' => $this->author ? [
                'id' => $this->author->id,
                'name' => $this->author->name,
                'slug' => $this->author->slug,
                'short_name' => $this->author->short_name,
                'initials' => $this->author->initials,
                'avatar_class' => $this->author->avatar_class,
                'line' => $this->author->line,
                'badge' => $this->author->badge,
                'bio' => $this->author->bio,
                'subtitle' => $this->author->subtitle,
                'photo_url' => $this->author->photo_url,
                'favorite_rank' => $this->author->favorite_rank,
            ] : null,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'icon' => $this->category->icon,
            ] : null,
            'card' => $this->card,
            'showcase' => $this->showcase,
        ];
    }

    /**
     * Filter by one or more category slugs (comma separated).
     */
    public function scopeCategorySlugs(Builder $query, ?string $slugs): Builder
    {
        $slugs = array_filter(explode(',', (string) $slugs));

        if ($slugs !== []) {
            $query->whereIn('category_id', Category::whereIn('slug', $slugs)->pluck('id'));
        }

        return $query;
    }

    /**
     * Filter by one or more formats (comma separated), OR semantics.
     */
    public function scopeFormats(Builder $query, ?string $formats): Builder
    {
        $formats = array_filter(explode(',', (string) $formats));

        if ($formats !== []) {
            $query->where(function (Builder $q) use ($formats) {
                foreach ($formats as $format) {
                    $q->orWhereJsonContains('formats', $format);
                }
            });
        }

        return $query;
    }

    /**
     * Search title, synopsis, author name, or category name.
     *
     * Multi-word terms use OR semantics between tokens so that quick-tag
     * labels like "Novel Pramoedya" or "Dongeng Anak" still return the
     * matching books even when only one token is present in the data.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $tokens = array_filter(preg_split('/\s+/', trim((string) $term)) ?: []);

        if ($tokens !== []) {
            $query->where(function (Builder $outer) use ($tokens) {
                foreach ($tokens as $token) {
                    $like = '%'.$token.'%';
                    $outer->orWhere(function (Builder $q) use ($like) {
                        $q->where('title', 'like', $like)
                            ->orWhere('synopsis', 'like', $like)
                            ->orWhereHas('author', fn (Builder $a) => $a->where('name', 'like', $like))
                            ->orWhereHas('category', fn (Builder $c) => $c->where('name', 'like', $like));
                    });
                }
            });
        }

        return $query;
    }

    /**
     * Sort using the catalog toolbar options.
     */
    public function scopeSort(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'terbaru' => $query->orderByDesc('year')->orderBy('popularity'),
            'rating' => $query->orderByDesc('rating')->orderByDesc('reviews_count'),
            'judul' => $query->orderBy('title'),
            'tahun' => $query->orderBy('year'),
            default => $query->orderBy('popularity'),
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'category_id',
        'book_id',
        'title',
        'author_name',
        'year',
        'source_url',
        'license_type',
        'notes',
        'manuscript_path',
        'manuscript_name',
        'manuscript_size',
        'manuscript_mime',
        'manuscript_hash',
        'cover_path',
        'cover_name',
        'cover_mime',
        'status',
        'reviewer_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'manuscript_size' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Shape one submission for the SPA.
     */
    public function toPayload(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author_name' => $this->author_name,
            'year' => $this->year,
            'source_url' => $this->source_url,
            'license_type' => $this->license_type,
            'notes' => $this->notes,
            'has_manuscript' => filled($this->manuscript_path),
            'manuscript_name' => $this->manuscript_name,
            'manuscript_size' => $this->manuscript_size,
            'manuscript_mime' => $this->manuscript_mime,
            'has_cover' => filled($this->cover_path),
            'cover_name' => $this->cover_name,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            'book' => $this->book ? [
                'id' => $this->book->id,
                'slug' => $this->book->slug,
                'title' => $this->book->title,
                'is_published' => $this->book->is_published,
            ] : null,
            'status' => $this->status,
            'reviewer_note' => $this->reviewer_note,
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'submitter' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'name',
        'initials',
        'avatar_class',
        'badge_text',
        'badge_class',
        'meta',
        'rating',
        'body',
        'helpfuls',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'float',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function helpfulVotes(): HasMany
    {
        return $this->hasMany(ReviewHelpful::class);
    }

    /**
     * Shape a review for the public book detail payload.
     */
    public function toPublicArray(?int $viewerId = null, bool $helpful = false): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'initials' => $this->initials,
            'avatar_class' => $this->avatar_class,
            'badge_text' => $this->badge_text,
            'badge_class' => $this->badge_class,
            'meta' => $this->meta,
            'rating' => $this->rating,
            'body' => $this->body,
            'helpfuls' => $this->helpfuls,
            'helpful' => $helpful,
            'is_mine' => $viewerId !== null && (int) $this->user_id === $viewerId,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_name',
        'initials',
        'avatar_class',
        'line',
        'badge',
        'bio',
        'subtitle',
        'photo_url',
        'favorite_rank',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}

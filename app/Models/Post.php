<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'views',
        'likes',
        'dislikes',
        'comments_count',
        'category_id',
    ];

    /**
     * Casts
     *
     * @var array<string,string>
     */
    protected $casts = [
        'views' => 'integer',
        'likes' => 'integer',
        'dislikes' => 'integer',
        'comments_count' => 'integer',
    ];

    /**
     * The user who authored the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke kategori (setiap post hanya punya satu kategori)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    /**
     * Relasi ke komentar (latest first)
     */
    public function comments(): HasMany
    {
        return $this->hasMany(\App\Models\Comment::class)->latest();
    }

    /**
     * Semua reaksi (likes + dislikes)
     *
     * NOTE:
     * - Saya menggunakan model App\Models\Reaction (bukan PostReaction)
     * - Pastikan Anda membuat model Reaction dan migration create_reactions_table
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(\App\Models\Reaction::class);
    }

    /**
     * Hanya yang like (reaction = 1)
     */
    public function likes(): HasMany
    {
        return $this->reactions()->where('reaction', 1);
    }

    /**
     * Hanya yang dislike (reaction = -1)
     */
    public function dislikes(): HasMany
    {
        return $this->reactions()->where('reaction', -1);
    }

    /**
     * Scope untuk filter berdasarkan slug kategori (opsional)
     */
    public function scopeFilterCategory($query, $categorySlug)
    {
        if (! $categorySlug) {
            return $query;
        }

        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }
}

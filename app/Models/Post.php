<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- tambah ini

class Post extends Model
{
    use HasFactory, SoftDeletes; // <-- pakai trait SoftDeletes

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
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke kategori (setiap post hanya punya satu kategori)
     */
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    /**
     * Relasi ke komentar
     */
    public function comments()
    {
        // latest() agar komentar terbaru muncul di atas
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Scope untuk filter berdasarkan slug kategori (opsional)
     */
    public function scopeFilterCategory($query, $categorySlug)
    {
        if (! $categorySlug) return $query;

        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    // tambahkan parent_id agar bisa digunakan untuk reply
    protected $fillable = ['post_id', 'user_id', 'body', 'parent_id'];

    /**
     * Relasi ke post yang dikomentari
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relasi ke user yang membuat komentar
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke komentar induk (jika ini adalah balasan)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Relasi ke balasan-balasannya (replies)
     */
    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    /**
     * Helper: cek apakah komentar masih bisa diedit (<= 1 menit)
     */
    public function canBeEdited(): bool
    {
        return $this->created_at && $this->created_at->greaterThan(now()->subMinute());
    }
}

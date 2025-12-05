<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaction extends Model
{
    protected $table = 'reactions'; // sesuai migration Anda
    public $timestamps = true;

    protected $fillable = [
        'post_id',
        'user_id',
        'reaction', // 1 atau -1
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

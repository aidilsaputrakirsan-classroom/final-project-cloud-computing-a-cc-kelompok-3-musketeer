<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'detail',
        'context',
        'ip_address',
        'user_agent',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'detail' => 'array',
    ];

    /**
     * Each log belongs to a user (if available).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

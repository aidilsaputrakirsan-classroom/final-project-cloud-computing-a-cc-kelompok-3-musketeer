<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLogService
{
    /**
     * Simpan activity log standar.
     *
     * @param  Authenticatable|int|null  $actor
     * @param  array<string,mixed>|string|null  $detail
     * @param  array{
     *     name?:string,
     *     user_id?:int,
     *     ip?:string,
     *     user_agent?:string
     * }  $context
     */
    public function log(
        Authenticatable|int|null $actor,
        string $action,
        string $description,
        array|string|null $detail = null,
        array $context = []
    ): ActivityLog {
        $detailPayload = $this->normalizeDetail($detail);
        $actorId = match (true) {
            is_int($actor) => $actor,
            $actor instanceof Authenticatable => $actor->getAuthIdentifier(),
            default => null,
        };

        return ActivityLog::create([
            'user_id' => $actorId
                ?? $context['user_id']
                ?? Auth::id(),
            'action' => Str::limit($action, 120, ''),
            'description' => $description,
            'detail' => $detailPayload,
            'context' => $context['name'] ?? $context['context'] ?? null,
            'ip_address' => $context['ip'] ?? request()?->ip(),
            'user_agent' => $context['user_agent'] ?? request()?->userAgent(),
        ]);
    }

    /**
     * Helper yang bisa dipanggil ketika hanya punya user_id.
     */
    public function logByUserId(
        ?int $userId,
        string $action,
        string $description,
        array|string|null $detail = null,
        array $context = []
    ): ActivityLog {
        return $this->log($userId, $action, $description, $detail, $context);
    }

    /**
     * Normalisasi detail agar valid untuk kolom JSON.
     *
     * @param  array<string,mixed>|string|null  $detail
     */
    private function normalizeDetail(array|string|null $detail): ?array
    {
        return match (true) {
            is_array($detail) => $detail,
            is_string($detail) => ['message' => $detail],
            default => null,
        };
    }
}


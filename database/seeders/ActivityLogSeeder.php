<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogSeeder extends Seeder
{
    public function run()
    {
        $actions = [
            'auth.login',
            'auth.logout',
            'post.created',
            'post.updated',
            'post.deleted',
            'comment.created',
            'comment.deleted',
            'report.submitted',
            'report.status_changed',
        ];

        $users = User::pluck('id')->toArray();

        for ($i = 0; $i < 30; $i++) {
            ActivityLog::create([
                'user_id' => rand(0, 10) > 2 ? $users[array_rand($users)] : null, 
                'action' => $actions[array_rand($actions)],
                'description' => 'Sample log ke-' . $i,
                'subject_type' => null,
                'subject_id' => null,
                'meta' => [
                    'ip' => '192.168.1.' . rand(2, 200),
                    'agent' => 'Mozilla/5.0'
                ],
            ]);
        }
    }
}

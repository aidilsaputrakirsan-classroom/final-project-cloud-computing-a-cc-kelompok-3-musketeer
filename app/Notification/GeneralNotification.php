<?php

namespace App\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

        public $type;
        public $message;
        public $extra;

    public function __construct(string $type, string $message, $extra = [])
    {
        $this->type = $type;
        $this->message = $message;
        $this->extra = $extra;
    }
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type'    => $this->type,       // contoh: post_deleted, like, comment
            'message' => $this->message,
            'post_id' => $this->extra['post_id'] ?? null,
            'time'    => now(),
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class FriendRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $sender;
    public $friendshipId;

    public function __construct($sender, $friendshipId)
    {
        $this->sender = $sender;
        $this->friendshipId = $friendshipId;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'sender_username' => $this->sender->username,
            'sender_avatar' => $this->sender->avatar_url,
            'friendship_id' => $this->friendshipId,
            'message' => "{$this->sender->name} (@{$this->sender->username}) wants to be your friend",
            'type' => 'friend_request',
            'action_url' => route('user.friends'),
            'created_at' => now()->toDateTimeString(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'type' => 'friend_request',
            'data' => [
                'sender_id' => $this->sender->id,
                'sender_name' => $this->sender->name,
                'sender_username' => $this->sender->username,
                'sender_avatar' => $this->sender->avatar_url,
                'friendship_id' => $this->friendshipId,
                'message' => "{$this->sender->name} wants to be your friend",
                'action_url' => route('user.friends'),
            ],
            'read_at' => null,
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'sender_username' => $this->sender->username,
            'sender_avatar' => $this->sender->avatar_url,
            'friendship_id' => $this->friendshipId,
            'message' => "{$this->sender->name} wants to be your friend",
            'type' => 'friend_request',
        ];
    }
}
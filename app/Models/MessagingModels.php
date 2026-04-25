<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// ──────────────────────────────────────────────────────────────────────────────
// CONVERSATION MODEL
// Messaging is ONLY allowed after interest is accepted.
// The interest_id FK enforces this relationship at DB level.
// ──────────────────────────────────────────────────────────────────────────────

class Conversation extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id', 'interest_id', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function interest()
    {
        return $this->belongsTo(Interest::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Get the other participant in the conversation.
     */
    public function getOtherUser(int $currentUserId): ?User
    {
        return $this->user_one_id === $currentUserId
            ? $this->userTwo
            : $this->userOne;
    }

    /**
     * Get or create a conversation between two users.
     * Ensures user_one_id < user_two_id for uniqueness.
     */
    public static function between(int $userA, int $userB): ?self
    {
        [$one, $two] = $userA < $userB ? [$userA, $userB] : [$userB, $userA];
        return static::where('user_one_id', $one)->where('user_two_id', $two)->first();
    }
}

// ──────────────────────────────────────────────────────────────────────────────
// MESSAGE MODEL
// ──────────────────────────────────────────────────────────────────────────────

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'conversation_id', 'sender_id',
        'body', 'type', 'attachment_path', 'read_at',
    ];

    protected $casts = ['read_at' => 'datetime'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    protected $fillable = ['conversation_id', 'sender_type', 'sender_id', 'body', 'type', 'is_internal', 'read_at', 'delivered_at'];

    protected function casts(): array
    {
        return ['is_internal' => 'boolean', 'read_at' => 'datetime', 'delivered_at' => 'datetime'];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ChatAttachment::class, 'message_id');
    }
}

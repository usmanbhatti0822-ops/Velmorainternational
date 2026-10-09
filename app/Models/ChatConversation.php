<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    protected $fillable = ['uuid', 'contact_id', 'visitor_token', 'status', 'assigned_to', 'product_id', 'inquiry_id', 'page_url', 'locale', 'country_code', 'ip_address', 'user_agent', 'rating', 'rating_comment', 'started_at', 'first_response_at', 'last_message_at', 'closed_at', 'unread_agent_count', 'unread_visitor_count'];

    protected function casts(): array
    {
        return ['started_at' => 'datetime', 'first_response_at' => 'datetime', 'last_message_at' => 'datetime', 'closed_at' => 'datetime'];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }
}

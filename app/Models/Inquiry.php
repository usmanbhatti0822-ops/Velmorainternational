<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use SoftDeletes;

    protected $fillable = ['reference', 'contact_id', 'status', 'priority', 'destination_port', 'delivery_timeline', 'message', 'locale', 'ip_address', 'user_agent', 'utm_source', 'utm_medium', 'utm_campaign', 'assigned_to', 'quoted_at', 'closed_at'];

    protected function casts(): array
    {
        return ['quoted_at' => 'datetime', 'closed_at' => 'datetime'];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InquiryItem::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(InquiryAttachment::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(InquiryNote::class);
    }
}

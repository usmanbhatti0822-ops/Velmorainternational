<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryAttachment extends Model
{
    protected $fillable = ['inquiry_id', 'disk', 'path', 'original_name', 'mime', 'size'];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryItem extends Model
{
    protected $fillable = ['inquiry_id', 'product_id', 'product_name_text', 'quantity', 'unit', 'supply_package_id', 'packaging_option_id', 'notes'];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

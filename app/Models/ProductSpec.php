<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpec extends Model
{
    use HasTranslations;

    protected $fillable = ['product_id', 'spec_key', 'spec_value', 'sort_order'];

    protected function casts(): array
    {
        return ['spec_key' => 'array', 'spec_value' => 'array'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

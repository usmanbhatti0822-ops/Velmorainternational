<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogDownload extends Model
{
    protected $fillable = ['catalog_id', 'contact_id', 'email', 'ip_address'];

    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class);
    }
}

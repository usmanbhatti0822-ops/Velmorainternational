<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PackagingOption extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'kind', 'size_value', 'size_unit', 'notes', 'is_active'];

    protected function casts(): array
    {
        return ['name' => 'array', 'notes' => 'array', 'is_active' => 'boolean'];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_packaging');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 */
class SupplyPackage extends Model
{
    use HasTranslations;

    protected $fillable = ['slug', 'name', 'audience', 'description', 'min_qty_note', 'icon', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['name' => 'array', 'audience' => 'array', 'description' => 'array', 'min_qty_note' => 'array', 'is_active' => 'boolean'];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_supply_package');
    }
}

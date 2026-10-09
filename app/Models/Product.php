<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $division_id
 * @property bool $is_active
 * @property string $slug
 */
class Product extends Model
{
    use HasTranslations;
    use SoftDeletes;

    protected $fillable = [
        'division_id',
        'slug',
        'name',
        'short_description',
        'description',
        'origin',
        'moq_value',
        'moq_unit',
        'lead_time_days',
        'is_featured',
        'is_active',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'short_description' => 'array',
            'description' => 'array',
            'seo_title' => 'array',
            'seo_description' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function specs(): HasMany
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort_order');
    }

    public function packagingOptions(): BelongsToMany
    {
        return $this->belongsToMany(PackagingOption::class, 'product_packaging')->where('is_active', true);
    }

    public function supplyPackages(): BelongsToMany
    {
        return $this->belongsToMany(SupplyPackage::class, 'product_supply_package')->where('is_active', true);
    }
}

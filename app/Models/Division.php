<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property bool $is_active
 * @property string $slug
 */
class Division extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug',
        'name',
        'summary',
        'description',
        'cover_image',
        'icon',
        'sort_order',
        'is_active',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'summary' => 'array',
            'description' => 'array',
            'seo_title' => 'array',
            'seo_description' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

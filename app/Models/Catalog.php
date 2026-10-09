<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $division_id
 * @property string $file_path
 * @property bool $is_active
 * @property bool $requires_email
 */
class Catalog extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'division_id', 'file_path', 'requires_email', 'is_active'];

    protected function casts(): array
    {
        return ['title' => 'array', 'requires_email' => 'boolean', 'is_active' => 'boolean'];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(CatalogDownload::class);
    }
}

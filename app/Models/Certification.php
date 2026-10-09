<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'issuer', 'file_path', 'logo_path', 'is_visible', 'sort_order'];

    protected function casts(): array
    {
        return ['name' => 'array', 'is_visible' => 'boolean'];
    }
}

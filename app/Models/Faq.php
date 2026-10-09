<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasTranslations;

    protected $fillable = ['scope', 'scope_id', 'question', 'answer', 'sort_order'];

    protected function casts(): array
    {
        return ['question' => 'array', 'answer' => 'array'];
    }
}

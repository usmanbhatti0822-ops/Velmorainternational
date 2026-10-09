<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'whatsapp', 'company', 'designation', 'country_code', 'city', 'first_seen_at', 'last_seen_at', 'source', 'marketing_opt_in'];

    protected function casts(): array
    {
        return ['first_seen_at' => 'datetime', 'last_seen_at' => 'datetime', 'marketing_opt_in' => 'boolean'];
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(ChatConversation::class);
    }
}

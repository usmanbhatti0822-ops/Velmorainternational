<?php

namespace App\Models;

trait HasTranslations
{
    public function localized(string $attribute, ?string $locale = null): string
    {
        $value = $this->getAttribute($attribute);

        if (! is_array($value)) {
            return is_string($value) ? $value : '';
        }

        $locale ??= app()->getLocale();

        return (string) ($value[$locale] ?? $value['en'] ?? reset($value) ?: '');
    }
}

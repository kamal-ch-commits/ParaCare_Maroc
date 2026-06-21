<?php

namespace App\Models\Concerns;

trait HasLocalizedAttributes
{
    protected function localizedValue(string $attribute): ?string
    {
        $translations = $this->getAttribute("{$attribute}_translations") ?? [];
        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        foreach (array_unique([$locale, $fallbackLocale, 'fr']) as $translationLocale) {
            $value = $translations[$translationLocale] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        $value = $this->getAttribute($attribute);

        return is_string($value) && trim($value) !== '' ? $value : null;
    }

    public function getTranslatedNameAttribute(): string
    {
        return $this->localizedValue('name') ?? '';
    }

    public function getTranslatedDescriptionAttribute(): ?string
    {
        return $this->localizedValue('description');
    }
}

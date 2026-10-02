<?php

namespace App\Models\Concerns;

/**
 * Gives models a Bengali value for content fields, falling back to English.
 *
 * Models expose accessors such as getTitleAttribute() which delegate here, so
 * views can keep using $model->title and automatically receive the value for
 * the active locale.
 */
trait HasLocalizedFields
{
    /**
     * Return the Bengali value for a field when available, otherwise English.
     */
    protected function localizedValue(?string $value, string $field): ?string
    {
        if (app()->getLocale() !== 'bn') {
            return $value;
        }

        $bengali = $this->attributes[$field . '_bn'] ?? null;

        return is_string($bengali) && trim($bengali) !== '' ? $bengali : $value;
    }
}

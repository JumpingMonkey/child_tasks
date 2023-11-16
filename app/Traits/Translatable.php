<?php

namespace App\Traits;
use Illuminate\Support\Facades\App;

trait Translatable
{
    /**
     * Translate model by current Locale
     *
     * @param object $model
     * @param array $without = ['created_at','updated_at']
     * @return array
     */
    public function translateModel($without = [])
    {
        if (!$this->isHasTranslationsTrait()) {
            return $this->getAttributes();
        }
        foreach ($this->getAttributes() as $key => $field) {
            if (!$this->isTranslatableAttribute($key) && !in_array($key, $without)) {
                $attributes[$key] = $field;
            }
        }
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, App::currentLocale());
        }
        return $attributes;
    }

    /**
     *
     * @return bool
     */
    private function isHasTranslationsTrait()
    {
        if (method_exists($this, 'isTranslatableAttribute') && method_exists($this, 'getTranslation') && method_exists($this, 'getLocale')) {
            return true;
        }
        return false;
    }
}


<?php

namespace App\Core\Settings\Services;

use App\Core\Settings\Models\Setting;

class SettingsService
{
    public function get(string $key, $default = null)
    {
        $setting = Setting::query()->where('key', $key)->first();

        return $setting ? $this->castValue($setting): $default;
    }

    public function set(
        string $key,
        $value,
        string $type = 'string',
        ?string $group = null
    )
    {
        return Setting::query()
            ->updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group'=>$group]
            );
    }

    protected function castValue(Setting $setting)
    {
        return match ($setting->type) {
            'integer' => (int) $setting->value,
            'boolean' => (bool) $setting->value,
            'json' => $setting->value !== null ? json_decode($setting->value, true) : null,
            default => $setting->value
        };
    }
}
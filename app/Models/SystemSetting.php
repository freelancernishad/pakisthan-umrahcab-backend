<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Retrieve a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Set or update a setting value by key.
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function setValue($key, $value)
    {
        $setting = static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::clearCache();
        return $setting;
    }

    /**
     * Clear the system settings cache.
     *
     * @return void
     */
    public static function clearCache()
    {
        \Illuminate\Support\Facades\Cache::forget('system_settings');
    }
}

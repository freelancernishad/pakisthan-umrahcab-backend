<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;

class UcWebsiteSetting extends Model
{
    protected $table = 'uc_website_settings';

    protected $fillable = ['key', 'value'];

    /**
     * Retrieve a setting value by key.
     */
    public static function getValue($key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Set or update a setting value by key.
     */
    public static function setValue($key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}

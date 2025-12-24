<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];
    
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return $setting->castValue($setting->value, $setting->type);
    }
    
    public static function setValue(string $key, $value, $group = 'general', $type = 'string')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['group' => $group, 'type' => $type]
        );
        
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
            $type = 'json';
        }
        
        $setting->update(['value' => $value, 'type' => $type]);
    }
    
    protected function castValue($value, $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}

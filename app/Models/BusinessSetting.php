<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    protected $fillable = ['type', 'value'];

    public static function getValue($type)
    {
        return optional(self::where('type', $type)->first())->value;
    }

    public static function setValue($type, $value)
    {
        return self::updateOrCreate(['type' => $type], ['value' => $value]);
    }
}


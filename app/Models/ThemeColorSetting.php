<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeColorSetting extends Model
{
    protected $table= 'theme_color_settings';
    protected $fillable = ['color'];
}

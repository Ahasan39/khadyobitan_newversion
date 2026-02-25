<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateLicense extends Model
{
    protected $fillable = ['version', 'license_key', 'is_used', 'used_at'];
}

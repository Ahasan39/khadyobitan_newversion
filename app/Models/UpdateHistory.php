<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateHistory extends Model
{
    protected $fillable = [
        'domain',
        'version',
        'ip_address',
        'user_agent',
        'updated_at_time',
    ];

    protected $casts = [
        'updated_at_time' => 'datetime',
    ];
}

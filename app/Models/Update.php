<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $fillable = [
    'version', 'title', 'description', 'file_path', 'force_update'
];

}

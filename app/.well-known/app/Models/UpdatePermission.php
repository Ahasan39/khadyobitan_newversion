<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdatePermission extends Model
{
    protected $fillable = ['key', 'name', 'status'];
}

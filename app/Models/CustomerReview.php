<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReview extends Model
{
        protected $fillable = ['images'];

        protected $casts = [
            'images' => 'array', 
        ];

    
}
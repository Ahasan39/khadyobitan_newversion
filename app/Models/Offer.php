<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['title', 'description'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product');
    }
}

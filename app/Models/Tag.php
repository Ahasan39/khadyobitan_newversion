<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
      protected $fillable = ['name', 'slug'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    
    public function categories() {
        return $this->belongsToMany(Category::class);
    }
    public function subcategories() {
        return $this->belongsToMany(SubCategory::class);
    }
    public function childcategories() {
        return $this->belongsToMany(ChildCategory::class);
    }
}

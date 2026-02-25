<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Response;
use Hash;
use Auth;
use Mail;
use Str;
use DB;

class ProductController extends Controller
{
   public function productFilters(Request $request)
{
    $query = Product::select(
        'id', 'slug', 'name','category_id', 'subcategory_id', 'childcategory_id','stock',
        'old_price', 'brand_id', 'new_price', 'category_id'
    )
    ->with([
        'image',
        'subcategory:id,name',
        'childcategory:id,name',
        'tags:id,name',
        'variables:id,product_id,size,color,new_price,old_price,stock,image',
        'brand:id,name'
    ]);

    /* -----------------------------------------
     * 1. Price Range Filter
     * -----------------------------------------*/
    if ($request->filled('min_price') && $request->filled('max_price')) {
        $query->whereBetween('new_price', [
            (float)$request->min_price,
            (float)$request->max_price
        ]);
    }

    /* -----------------------------------------
     * 2. Category Filters
     * -----------------------------------------*/
    if ($request->filled('category_ids')) {
        $query->whereIn('category_id', $request->category_ids);
    }

    if ($request->filled('subcategory_ids')) {
        $query->whereIn('subcategory_id', $request->subcategory_ids);
    }

    if ($request->filled('childcategory_ids')) {
        $query->whereIn('childcategory_id', $request->childcategory_ids);
    }

    /* -----------------------------------------
     * 3. Brand Filter
     * -----------------------------------------*/
    if ($request->filled('brand_ids')) {
        $query->whereIn('brand_id', $request->brand_ids);
    }

    /* -----------------------------------------
     * 4. Tag Filter (product_tag)
     * -----------------------------------------*/
    if ($request->filled('tag_ids')) {
        $query->whereHas('tags', function ($q) use ($request) {
            $q->whereIn('tags.id', $request->tag_ids);
        });
    }

    /* -----------------------------------------
     * 5. Variable Filter (Color / Size)
     * product_variables table
     * -----------------------------------------*/
    if ($request->filled('colors')) {
        $query->whereHas('variables', function ($q) use ($request) {
            $q->whereIn('color', $request->colors);
        });
    }

    if ($request->filled('sizes')) {
        $query->whereHas('variables', function ($q) use ($request) {
            $q->whereIn('size', $request->sizes);
        });
    }

    /* -----------------------------------------
     * 6. Lazy Loading (pagination)
     * -----------------------------------------*/
    $limit = $request->get('per_page', 20);
    $products = $query->paginate($limit);

    return response()->json([
        'success' => true,
        'filters' => $request->all(),
        'data' => $products->items(),
        'total' => $products->total(),
        'pagination' => [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
        ]
    ]);
}


}

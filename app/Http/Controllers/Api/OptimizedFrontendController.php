<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Optimized Frontend Controller
 * 
 * This controller implements performance best practices:
 * - Query result caching
 * - Eager loading relationships
 * - Pagination
 * - Selective column loading
 * - Response caching
 */
class OptimizedFrontendController extends Controller
{
    /**
     * Cache duration in minutes
     */
    private const CACHE_DURATION = 60; // 1 hour
    private const CACHE_DURATION_SHORT = 15; // 15 minutes

    /**
     * Get all products with optimization
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function products()
    {
        $cacheKey = 'api.products.all';
        
        $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            return Product::where('status', 1)
                ->select('id', 'slug', 'name', 'topsale', 'old_price', 'new_price', 'stock')
                ->with([
                    'image:id,product_id,image',
                    'variables:id,product_id,color_id,size_id,purchase_price,price,stock'
                ])
                ->paginate(20);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $data
        ]);
    }

    /**
     * Get products by category with optimization
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryProducts($id)
    {
        $cacheKey = "api.category.{$id}.products";
        
        $result = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($id) {
            $category = Category::where(['status' => 1, 'id' => $id])
                ->select('id', 'name', 'slug')
                ->first();

            if (!$category) {
                return null;
            }

            $products = Product::where(['status' => 1, 'category_id' => $category->id])
                ->select('id', 'slug', 'stock', 'name', 'subcategory_id', 'childcategory_id', 
                        'old_price', 'brand_id', 'new_price', 'category_id')
                ->with([
                    'image:id,product_id,image',
                    'subcategory:id,name,slug',
                    'childcategory:id,name,slug',
                    'tags:id,name',
                    'variables:id,product_id,color_id,size_id,price,stock',
                    'brand:id,name,slug'
                ])
                ->orderBy('id', 'DESC')
                ->paginate(20);

            return [
                'category' => $category,
                'products' => $products
            ];
        });

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $result['products'],
            'category' => $result['category']
        ]);
    }

    /**
     * Get products by subcategory with optimization
     * 
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function subcategoryProducts($slug)
    {
        $cacheKey = "api.subcategory.{$slug}.products";
        
        $result = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($slug) {
            $subCategory = Subcategory::where(['status' => 1, 'slug' => $slug])
                ->select('id', 'name', 'slug')
                ->first();

            if (!$subCategory) {
                return null;
            }

            $products = Product::where(['status' => 1, 'subcategory_id' => $subCategory->id])
                ->select('id', 'slug', 'name', 'old_price', 'new_price', 'subcategory_id', 'stock')
                ->with([
                    'image:id,product_id,image',
                    'variables:id,product_id,color_id,size_id,price,stock'
                ])
                ->orderBy('id', 'DESC')
                ->paginate(20);

            return [
                'category' => $subCategory,
                'products' => $products
            ];
        });

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subcategory not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $result['products'],
            'category' => $result['category']
        ]);
    }

    /**
     * Get products by childcategory with optimization
     * 
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function childcategoryProducts($slug)
    {
        $cacheKey = "api.childcategory.{$slug}.products";
        
        $result = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($slug) {
            $childCategory = Childcategory::where(['status' => 1, 'slug' => $slug])
                ->select('id', 'name', 'slug')
                ->first();

            if (!$childCategory) {
                return null;
            }

            $products = Product::where(['status' => 1, 'childcategory_id' => $childCategory->id])
                ->select('id', 'slug', 'name', 'old_price', 'new_price', 'childcategory_id', 'stock')
                ->with([
                    'image:id,product_id,image',
                    'variables:id,product_id,color_id,size_id,price,stock'
                ])
                ->orderBy('id', 'DESC')
                ->paginate(20);

            return [
                'category' => $childCategory,
                'products' => $products
            ];
        });

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Child category not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $result['products'],
            'category' => $result['category']
        ]);
    }

    /**
     * Get products by brand with optimization
     * 
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function brandProducts($slug)
    {
        $cacheKey = "api.brand.{$slug}.products";
        
        $result = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($slug) {
            $brand = Brand::where(['status' => 1, 'slug' => $slug])
                ->select('id', 'name', 'slug')
                ->first();

            if (!$brand) {
                return null;
            }

            $products = Product::where(['status' => 1, 'brand_id' => $brand->id])
                ->select('id', 'slug', 'name', 'old_price', 'new_price', 'brand_id', 'stock')
                ->with([
                    'image:id,product_id,image',
                    'variables:id,product_id,color_id,size_id,price,stock'
                ])
                ->orderBy('id', 'DESC')
                ->paginate(20);

            return [
                'category' => $brand,
                'products' => $products
            ];
        });

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Brand not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $result['products'],
            'category' => $result['category']
        ]);
    }

    /**
     * Get featured products with optimization
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function featuredProducts()
    {
        $cacheKey = 'api.products.featured';
        
        $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            return Product::where(['status' => 1, 'feature_product' => 1])
                ->select('id', 'slug', 'name', 'old_price', 'new_price', 'stock')
                ->with('image:id,product_id,image')
                ->limit(20)
                ->get();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $data
        ]);
    }

    /**
     * Get top rated products with optimization
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function topRatedProducts()
    {
        $cacheKey = 'api.products.top_rated';
        
        $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            return Product::where(['status' => 1, 'top_rated' => 1])
                ->select('id', 'slug', 'name', 'old_price', 'new_price', 'stock')
                ->with('image:id,product_id,image')
                ->limit(20)
                ->get();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $data
        ]);
    }

    /**
     * Clear all product caches
     * This should be called when products are updated
     * 
     * @return void
     */
    public static function clearProductCache()
    {
        Cache::forget('api.products.all');
        Cache::forget('api.products.featured');
        Cache::forget('api.products.top_rated');
        
        // Clear category caches
        $categories = Category::pluck('id');
        foreach ($categories as $id) {
            Cache::forget("api.category.{$id}.products");
        }
        
        // Clear subcategory caches
        $subcategories = Subcategory::pluck('slug');
        foreach ($subcategories as $slug) {
            Cache::forget("api.subcategory.{$slug}.products");
        }
        
        // Clear childcategory caches
        $childcategories = Childcategory::pluck('slug');
        foreach ($childcategories as $slug) {
            Cache::forget("api.childcategory.{$slug}.products");
        }
        
        // Clear brand caches
        $brands = Brand::pluck('slug');
        foreach ($brands as $slug) {
            Cache::forget("api.brand.{$slug}.products");
        }
    }
}

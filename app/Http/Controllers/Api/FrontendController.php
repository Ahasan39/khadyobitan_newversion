<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GeneralSetting;
use App\Models\Banner;
use App\Models\BannerCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Color;
use App\Models\Product;
use App\Models\CreatePage;
use App\Models\SocialMedia;
use App\Models\Contact;
use App\Models\CouponCode;
use App\Models\CustomerProductReview;
use App\Models\Review;
use App\Models\District;
use App\Models\Size;
use App\Models\Tag;
use App\Models\Subcategory;
use App\Models\CustomerReview;
use App\Models\UserMessage;
use App\Models\Notice;
use App\Models\GoogleTagManager;
use Carbon\Carbon; 
use Response;
use Hash;
use Auth;
use Mail;
use Str;
use DB;

class FrontendController extends Controller
{
    public function appconfig()
    {
        $data = GeneralSetting::where('status', 1)->select('id', 'name', 'white_logo', 'dark_logo', 'favicon')->first();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }

    public function slider()
    {
        $data = Banner::where(['status' => 1, 'category_id' => 1])->select('id', 'image', 'category_id','title','sub_title', 'slug', 'link', 'button_link')->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }

    //banner API
    public function banner($id = null)
    {
        $data = Banner::where(['status' => 1, 'category_id' => 2])->select('id', 'image', 'status', 'category_id', 'link')->with('category')->get();
        return response()->json([
            'status' => 'success', 
            'message' => 'Data fatch successfully',
            'data' => $data,
            ]);
        
    }

    public function category()
{
    $data = Category::where('status', 1)
        ->select('id', 'slug', 'name', 'image')
        ->withCount('productsCount') 
        ->with('tags')
        ->get();

    return response()->json([
        'status' => 'success',
        'message' => 'Data fetch successfully',
        'data' => $data
    ]);
}


    //feroj works =========================
    
    //product by Category API
    // public function productsbycategory($id)
    // {
    //     $data = Product::where(['status' => 1, 'category_id' => $id])->select('id', 'slug', 'name', 'old_price', 'new_price', 'category_id')->with(['image', 'category:id,name'])->orderBy('id', 'DESC')->get();
    //     return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    // }


    public function productsbycategory($id)
    {
        $category = Category::where(['status' => 1, 'id' => $id])->select('id', 'name', 'slug')->first();
        $data = Product::where(['status' => 1, 'category_id' => $category->id])->select('id', 'slug','stock', 'name','subcategory_id','childcategory_id', 'old_price','brand_id', 'new_price', 'category_id')->with('image', 'subcategory', 'childcategory', 'tags','variables','brand')->orderBy('id', 'DESC')->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data, 'category' => $category]);
    }


    public function allsingleProduct($productSlug)
    {
        
        // Find the product inside this category
        $product = Product::where([
        'status' => 1,
        'slug' => $productSlug,
        ])
        ->with(['category', 'subcategory', 'childcategory','brand', 'image', 'images', 'reviews','variables','policies','tags'])
        ->select('id', 'slug', 'name', 'description', 'old_price', 'new_price', 'stock', 'pro_video', 'pro_barcode', 'category_id', 'subcategory_id', 'childcategory_id','brand_id')
        ->first();

        if ($product) {
            // Group by size and map colors
            $colorSize = $product->variables
                ->groupBy('size')
                ->map(function ($items, $size) {
                    return [
                        'size' => $size,
                        'colors' => $items->pluck('color')->unique()->values(),
                    ];
                })
                ->values(); // convert to simple array

            return response()->json([
                'status' => 'success',
                'message' => 'Product details fetched successfully',
                'product' => $product,
                'color & size' => $colorSize,
            ]);
        }

    }


    //sub category
    public function subcategory()
    {
        $data = Subcategory::where(['status' => 1])->select('id', 'slug', 'name', 'image','category_id')->with(['category:id,slug,name','tags'])->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }
    
    //product by subCategory API
    public function productsbySubcategory($slug)
    {
        $subCategory = Subcategory::where(['status' => 1, 'slug' => $slug])->select('id', 'name', 'slug')->first();
        $data = Product::where(['status' => 1, 'subcategory_id' => $subCategory->id])->select('id', 'slug', 'name', 'old_price', 'new_price', 'subcategory_id')->with('image','variables')->orderBy('id', 'DESC')->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data, 'category' => $subCategory]);

    }

    //child category
    public function childcategory()
    {
        $data = Childcategory::where(['status' => 1])->select('id', 'slug', 'name', 'image','subcategory_id')->with(['subcategory:id,slug,name','tags'])->get();
        return response()->json([
            'status' => 'success',
             'message' => 'Data fatch successfully', 
             'data' => $data
            ]);
    }


    //product by childCategory API
     public function productsbyChildcategory($slug)
    {
        
        $childCategory = Childcategory::where(['status' => 1, 'slug' => $slug])->select('id', 'name', 'slug')->first();
        $data = Product::where(['status' => 1, 'childcategory_id' => $childCategory->id])->select('id', 'slug', 'name', 'old_price', 'new_price', 'childcategory_id')->with('image','variables')->orderBy('id', 'DESC')->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data, 'category' => $childCategory]);

    }

    //brand api
    public function brand()
    {
        $data = Brand::where(['status' => 1])->select('id', 'slug', 'name', 'image')->get();
        return response()->json([
            'status' => 'success',
             'message' => 'Data fatch successfully', 
             'data' => $data
            ]);
    }
    

    //product by brand API
    public function productsbyBrand($slug)
    {

        $brand = Brand::where(['status' => 1, 'slug' => $slug])->select('id', 'name', 'slug')->first();
        $data = Product::where(['status' => 1, 'brand_id' => $brand->id])->select('id', 'slug', 'name', 'old_price', 'new_price', 'brand_id')->with('image','variables')->orderBy('id', 'DESC')->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data, 'category' => $brand]);
    }

    //color api
    public function color()
    {
        $data = Color::where(['status' => 1])->select('id', 'name', 'color')->get();
        return response()->json([
            'status' => 'success',
             'message' => 'Data fatch successfully', 
             'data' => $data
            ]);
    }
    
    //size api
    public function size()
    {
        $data = Size::where(['status' => 1])->select('id', 'name')->get();
        return response()->json([
            'status' => 'success',
             'message' => 'Data fatch successfully', 
             'data' => $data
            ]);
    }
    
    //review api
    public function review()
    {
        $data = Review::where(['status' => 1])
            ->select('id', 'name', 'email', 'ratting', 'review', 'product_id', 'customer_id')
            ->with([
                'product:id,name,status', 
                'customer:id,name,email,phone,status'
            ])
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetch successfully',
            'data' => $data
        ]);
    }


    //global search API
public function globalSearch(Request $request)
{
    $request->validate([
        'keyword' => 'required|string|min:2|max:100',
    ]);

    $keyword = trim($request->keyword);
    $limit = min($request->get('limit'), 100);

    /** -------------------------------------------------------
     * Generate 3-Letter Chunks for partial matching
     * ------------------------------------------------------- */
    $threeLetterChunks = [];
    if (strlen($keyword) >= 3) {
        for ($i = 0; $i <= strlen($keyword) - 3; $i++) {
            $threeLetterChunks[] = substr($keyword, $i, 3);
        }
    }

    /** Break multi-word keyword */
    $words = array_filter(explode(' ', $keyword), fn($w) => strlen($w) > 2);

    /** -------------------------------------------------------
     * Fetch IDs by matching keyword
     * ------------------------------------------------------- */

    // Tags
    $tagIds = Tag::where('name', 'LIKE', "%$keyword%")->pluck('id');

    // Category
    $categoryIds = Category::where('name', 'LIKE', "%$keyword%")->pluck('id');

    // Brand
    $brandIds = Brand::where('name', 'LIKE', "%$keyword%")->pluck('id');

    // SubCategory
    $subCategoryIds = SubCategory::where('name', 'LIKE', "%$keyword%")->pluck('id');

    // ChildCategory
    $childCategoryIds = ChildCategory::where('name', 'LIKE', "%$keyword%")->pluck('id');

    // Category Tag Relation
    $categoryIdsByTag = DB::table('category_tag')
        ->whereIn('tag_id', $tagIds)
        ->pluck('category_id');

    // Subcategory Tag Relation
    $subCategoryIdsByTag = DB::table('subcategory_tag')
        ->whereIn('tag_id', $tagIds)
        ->pluck('subcategory_id');

    // Childcategory Tag Relation
    $childCategoryIdsByTag = DB::table('childcategory_tag')
        ->whereIn('tag_id', $tagIds)
        ->pluck('childcategory_id');

    /** -------------------------------------------------------
     * FINAL PRODUCT QUERY
     * ------------------------------------------------------- */
    $products = Product::select(
            'id', 'slug','stock', 'name', 'old_price', 'new_price',
            'brand_id', 'category_id', 'subcategory_id', 'childcategory_id'
        )
        ->with([
            'category:id,name',
            'subcategory:id,name',
            'childcategory:id,name',
            'brand:id,name',
            'image',
            'tags:id,name',
            'variables'
        ])

        ->where(function ($query) use (
            $keyword, $words, $threeLetterChunks,
            $tagIds, $brandIds,
            $categoryIds, $subCategoryIds, $childCategoryIds,
            $categoryIdsByTag, $subCategoryIdsByTag, $childCategoryIdsByTag
        ) {

            /** 1. Direct name / slug match */
            $query->where('name', 'LIKE', "%$keyword%")
                  ->orWhere('slug', 'LIKE', "%$keyword%");

            /** 2. SOUND-EX typo-safe search */
            $query->orWhereRaw("SOUNDEX(name) = SOUNDEX(?)", [$keyword]);

            /** 3. ANY 3-letter match (SUPER IMPORTANT FEATURE) */
            if (!empty($threeLetterChunks)) {
                $query->orWhere(function ($q) use ($threeLetterChunks) {
                    foreach ($threeLetterChunks as $chunk) {
                        $q->orWhere('name', 'LIKE', "%$chunk%");
                    }
                });
            }

            /** 4. Multi-word split matching */
            if (!empty($words)) {
                $query->orWhere(function ($q) use ($words) {
                    foreach ($words as $word) {
                        $q->orWhere('name', 'LIKE', "%$word%");
                    }
                });
            }

            /** 5. Tag match → product */
            if ($tagIds->isNotEmpty()) {
                $query->orWhereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                });
            }

            /** 6. Brand name match */
            if ($brandIds->isNotEmpty()) {
                $query->orWhereIn('brand_id', $brandIds);
            }

            /** 7. Category/SubCategory/ChildCategory name match */
            if ($categoryIds->isNotEmpty()) {
                $query->orWhereIn('category_id', $categoryIds);
            }

            if ($subCategoryIds->isNotEmpty()) {
                $query->orWhereIn('subcategory_id', $subCategoryIds);
            }

            if ($childCategoryIds->isNotEmpty()) {
                $query->orWhereIn('childcategory_id', $childCategoryIds);
            }

            /** 8. Tag → Category/SubCategory/ChildCategory relation */
            if ($categoryIdsByTag->isNotEmpty()) {
                $query->orWhereIn('category_id', $categoryIdsByTag);
            }

            if ($subCategoryIdsByTag->isNotEmpty()) {
                $query->orWhereIn('subcategory_id', $subCategoryIdsByTag);
            }

            if ($childCategoryIdsByTag->isNotEmpty()) {
                $query->orWhereIn('childcategory_id', $childCategoryIdsByTag);
            }
        })

        // Smart sorting
        ->orderByRaw("
            CASE 
                WHEN name = ? THEN 1
                WHEN name LIKE ? THEN 2
                WHEN slug LIKE ? THEN 3
                ELSE 4
            END
        ", [$keyword, "$keyword%", "$keyword%"])

        ->limit($limit)
        ->get();
        // -----------------------------
// FILTER DATA GENERATION
// -----------------------------

$prices = collect();
$colors = collect();
$sizes  = collect();
$tags   = collect();

foreach ($products as $product) {

    // Product base price
    if (!is_null($product->new_price)) {
        $prices->push($product->new_price);
    }

    // Variable prices, colors, sizes
    foreach ($product->variables as $variable) {
        if (!is_null($variable->new_price)) {
            $prices->push($variable->new_price);
        }

        if (!empty($variable->color)) {
            $colors->push($variable->color);
        }

        if (!empty($variable->size)) {
            $sizes->push($variable->size);
        }
    }

    // Tags
    foreach ($product->tags as $tag) {
        $tags->push($tag->name);
    }
}

// Final filter values
$filterData = [
    'min_price' => $prices->min(),
    'max_price' => $prices->max(),

    'colors' => $colors->unique()->values(),
    'sizes'  => $sizes->unique()->values(),
    'tags'   => $tags->unique()->values(),
];

    return response()->json([
        'success' => true,
        'message' => 'Search results for: ' . $keyword,
         'filters' => $filterData,
        'data' => [
            'products' => $products,
            'total' => $products->count(),
            'keyword' => $keyword,
            // FILTER DATA
       
        ],
    ]);
}




/** ---------------------------------------------
 * ALTERNATIVE: PHP-based Levenshtein (if needed)
 * --------------------------------------------- */
public function globalSearchWithLevenshtein(Request $request)
{
    $request->validate([
        'keyword' => 'required|string|min:2|max:100',
    ]);

    $keyword = trim($request->keyword);
    $limit = min($request->get('limit', 10), 100);

    // Get broader results first
    $products = Product::select('id', 'slug', 'name', 'old_price', 'new_price',
            'brand_id', 'category_id', 'subcategory_id', 'childcategory_id')
        ->where('name', 'LIKE', "%$keyword%")
        ->orWhere('slug', 'LIKE', "%$keyword%")
        ->orWhereRaw("SOUNDEX(name) = SOUNDEX(?)", [$keyword])
        ->limit($limit * 3) // Get more for filtering
        ->get();

    // Apply Levenshtein distance in PHP
    $filtered = $products->filter(function ($product) use ($keyword) {
        return levenshtein(
            strtolower($product->name), 
            strtolower($keyword)
        ) <= 3; // Allow up to 3 character differences
    })->take($limit);

    // Load relationships only for filtered results
    $filtered->load([
        'category:id,name', 
        'subcategory:id,name', 
        'childcategory:id,name',
        'brand:id,name', 
        'image', 
        'tags:id,name', 
        'variables'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Search results for: ' . $keyword,
        'data' => [
            'products' => $filtered->values(),
            'total' => $filtered->count(),
        ],
    ]);
}





    //featured product
    public function featuredproduct($id = null)
    {
        // $data = Product::where(['status' => 1, 'feature_product' => 1])->select('id', 'slug', 'name', 'old_price', 'new_price')->with('image')->get();
        // return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);


        if ($id) {
            // Single latest product by ID
            $data = Product::where([
                        'status' => 1,
                        'feature_product' => 1,
                        'id' => $id
                    ])
                    ->select('id', 'slug', 'name','stock', 'old_price', 'new_price', 'warranty','subcategory_id','childcategory_id', 'description','category_id', 'brand_id')
                    ->with('image', 'category', 'subcategory', 'childcategory', 'brand', 'reviews','variables','tags')
                     ->withSum('variables as variables_sum_stock', 'stock')  
                    ->first();

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Single  product fetched successfully',
                'data' => $data
            ]);
        }

        // All latest products
        $data = Product::where(['status' => 1, 'feature_product' => 1])
                    ->select('id', 'slug', 'name','stock','category_id', 'subcategory_id','childcategory_id','old_price', 'new_price')
                    ->with('image','variables', 'category',  'subcategory', 'childcategory','tags')
                     ->withSum('variables as variables_sum_stock', 'stock')  
                    ->orderBy('id', 'DESC')
                    ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $data
        ]);

        
    }
   

    //latest product
    
    public function latestProduct($slug = null)
    {
        // ðŸ”¹ if product have slug 
        if ($slug) {
            $product = Product::where([
                    'status' => 1,
                    'new_arrival' => 1,
                    'slug' => $slug
                ])
                ->select(
                    'id', 'slug', 'name','stock', 'old_price', 'subcategory_id','childcategory_id','new_price', 
                    'warranty', 'description', 'stock', 
                    'pro_barcode', 'pro_video', 'category_id', 'brand_id'
                )
                ->with(['category', 'brand',  'subcategory', 'childcategory','image', 'reviews', 'variables','tags'])
                 ->withSum('variables as variables_sum_stock', 'stock')  
                ->first();

            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                    'data' => null
                ], 404);
            }

            //  size wise color grouping
            $colorSize = $product->variables
                ->groupBy('size')
                ->map(function ($items, $size) {
                    return [
                        'size' => $size,
                        'colors' => $items->pluck('color')->unique()->values(),
                    ];
                })
                ->values(); 

          
            return response()->json([
                'status' => 'success',
                'message' => 'Product details fetched successfully',
                'product' => $product,
                'color_size_map' => $colorSize,
            ]);
        }

        // ith wout slug show all latest product
        $data = Product::where(['status' => 1, 'new_arrival' => 1])
            ->select('id', 'slug', 'name','stock','category_id','subcategory_id','childcategory_id', 'old_price', 'new_price','brand_id')
            ->with('image','variables', 'category',  'subcategory', 'childcategory','tags' , 'brand')
             ->withSum('variables as variables_sum_stock', 'stock')  
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Latest products fetched successfully',
            'data' => $data
        ]);
    }


    //popular product
    public function popularproduct($id = null)
    {
        if ($id) {
            // Single latest product by ID
            $data = Product::where([
                        'status' => 1,
                        'top_selling' => 1,
                        'id' => $id
                    ])
                    ->select('id', 'slug', 'name','stock', 'old_price', 'new_price', 'warranty', 'description','category_id', 'brand_id')
                    ->with('image', 'category', 'brand', 'reviews','variables','tags')
                     ->withSum('variables as variables_sum_stock', 'stock')  
                    ->first();

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Single  product fetched successfully',
                'data' => $data
            ]);
        }

        // All latest products
        $data = Product::where(['status' => 1, 'top_selling' => 1])
                    ->select('id', 'slug','stock','category_id','subcategory_id','childcategory_id', 'name', 'old_price', 'new_price')
                    ->with('image','variables', 'category','subcategory', 'childcategory','tags')
                     ->withSum('variables as variables_sum_stock', 'stock')  
                    ->orderBy('id', 'DESC')
                    ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $data
        ]);

        
    }

    //trending product
    public function trendingproduct($id = null)
    {
        

        if ($id) {
            // Single latest product by ID
            $data = Product::where([
                        'status' => 1,
                        'topsale' => 1,
                        'id' => $id
                    ])
                    ->select('id', 'slug', 'name','stock', 'old_price', 'new_price', 'warranty', 'description','category_id', 'brand_id', 'subcategory_id','childcategory_id')
                    ->with('image', 'category',   'subcategory', 'childcategory','brand', 'reviews','variables','tags')
                     ->withSum('variables as variables_sum_stock', 'stock')  
                    ->first();

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Single data fetched successfully',
                'data' => $data
            ]);
        }

        // All latest products
        $data = Product::where(['status' => 1, 'topsale' => 1])
                    ->select('id', 'slug', 'name','stock','category_id', 'brand_id', 'subcategory_id','childcategory_id','old_price', 'new_price')
                    ->with('image','variables','category', 'subcategory', 'childcategory','tags')
                     ->withSum('variables as variables_sum_stock', 'stock')  
                    ->orderBy('id', 'DESC')
                    ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data fatch successfully',
            'data' => $data
        ]);
        
    }


    //best selling product
    public function bestSellingProduct($id = null)
    {
        // $data = Product::where(['status' => 1, 'top_rated' => 1])->select('id', 'slug', 'name', 'old_price', 'new_price')->with('image')->get();
        // return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);

        if ($id) {
            // Single latest product by ID
            $data = Product::where([
                        'status' => 1,
                        'top_rated' => 1,
                        'id' => $id
                    ])
                    ->select('id', 'slug', 'name', 'stock', 'old_price', 'new_price', 'warranty', 'description','category_id', 'brand_id' ,'category_id', 'brand_id', 'subcategory_id','childcategory_id')
                    ->with('image', 'category',  'subcategory', 'childcategory', 'brand', 'reviews','variables','tags')
                     ->withSum('variables as variables_sum_stock', 'stock')  
                    ->first();

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Single  product fetched successfully',
                'data' => $data
            ]);
        }

        // All latest products
        $data = Product::where(['status' => 1, 'top_rated' => 1])
                    ->select('id', 'slug', 'name','stock','category_id','subcategory_id','childcategory_id', 'old_price', 'new_price')
                    ->with('image','variables','category', 'subcategory', 'childcategory','tags')
                     ->withSum('variables as variables_sum_stock', 'stock')  
                    ->orderBy('id', 'DESC')
                    ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => $data
        ]);


    }

    
    //stock product
    public function stockProduct()
    {
        $data = Product::where('status', 1)
                      ->where('stock', '>', 0)
                      ->select('id', 'slug', 'name', 'stock', 'old_price', 'new_price')
                      ->with('image')
                      ->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }

    //stock out product
    public function stockOutProduct()
    {
        $data = Product::where('status', 1)
                      ->where('stock', '<=', 0)
                      ->select('id', 'slug', 'name', 'stock', 'old_price', 'new_price')
                      ->with('image')
                      ->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }   

    //all products
   public function allProducts()
{
    $data = Product::where('status', 1)
        ->select('id', 'slug', 'name', 'stock', 'old_price', 'new_price',
            'category_id', 'brand_id', 'subcategory_id', 'childcategory_id')
        ->with('image', 'brand', 'category', 'subcategory', 'childcategory', 'variables', 'tags')
        ->withSum('variables as variables_sum_stock', 'stock')   
        ->orderBy('id', 'DESC')
        ->get();

    return response()->json([
        'status' => 'success',
        'message' => 'Data fetch successfully',
        'data' => $data,
    ]);
}


    //single product
    public function singleProduct($slug)
    {
        $product = Product::where(['status' => 1, 'slug' => $slug])
            ->with(['image','images', 'category','subcategory', 'childcategory', 'brand', 'reviews','variables','tags'])
             ->withSum('variables as variables_sum_stock', 'stock') 
            ->first();

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetch successfully',
            'data' => $product
        ]);
    }

    
    //product Filter by Category
    public function filterByCategory($id)
    {
        $data = Product::where('status', 1)
            ->where('category_id', $id)
            ->select('id', 'slug', 'name', 'old_price', 'new_price', 'category_id')
            ->with(['image', 'category'])
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetch successfully',
            'data' => $data
        ]);
    }

    //product Filter by Price Range
    public function filterByPriceRange(Request $request)
    {
        $min = $request->query('min', 0);
        $max = $request->query('max', 10000000); 

        $products = Product::where('status', 1)
            ->whereBetween('new_price', [$min, $max])
            ->select('id', 'slug', 'name', 'old_price', 'new_price', 'category_id')
            ->with(['image', 'category'])
            ->orderBy('new_price', 'ASC')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Products filtered by price range successfully',
            'data' => $products
        ]);
    }

    public function sortProducts(Request $request)
    {
        $sortBy = $request->query('by', 'price');   // default 'price'
        $order = $request->query('order', 'asc');   // asc / desc

        $products = Product::where('status', 1)
            ->select('id', 'slug', 'name', 'old_price', 'new_price', 'category_id')
            ->with(['image', 'category'])
            ->when($sortBy === 'price', function ($query) use ($order) {
                $query->orderBy('new_price', $order);
            })
            ->when($sortBy === 'name', function ($query) use ($order) {
                $query->orderBy('name', $order);
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Products sorted successfully',
            'data' => $products
        ]);
    }

   
    
    public function hotdealproduct()
    {
        $data = Product::where(['status' => 1])->select('id', 'slug', 'name', 'topsale', 'old_price', 'new_price')->with('image','variables')->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }

    public function homepageproduct()
    {
        $data = Category::where(['status' => 1])->select('id', 'slug', 'name')->with('products', 'products.image')->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }

    public function footermenuleft()
    {
        $data = CreatePage::where(['status' => 1])->select('id', 'slug', 'name')->limit(3)->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }
    
    public function footermenuright()
    {
        $data = CreatePage::where(['status' => 1])->select('id', 'slug', 'name')->skip(3)->limit(10)->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }
    
    public function socialmedia()
    {
        $data = SocialMedia::where(['status' => 1])->select('id', 'title', 'icon', 'link', 'color')->get();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }
    
    public function contactinfo()
    {
        $data = Contact::where(['status' => 1])->first();
        return response()->json(['status' => 'success', 'message' => 'Data fatch successfully', 'data' => $data]);
    }

    public function coupon()
    {
        $findcoupon = CouponCode::where('status', 1)->get();
        return response()->json([
            "status" => "success",
            "coupon" => $findcoupon,
        ]);
    }

    //footer logo
   public function footerLogo()
    {
        $contact = Contact::where('status', 1)->first();
        $general = GeneralSetting::where('status', 1)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Data fetched successfully',
            'data' => [
                'contact' => $contact,
                'general_setting' => $general
            ]
        ]);
    }

    public function singlePage($slug)
    {
        $page = CreatePage::where('slug', $slug)
                    ->where('status', 1)
                    ->select('id', 'name', 'slug', 'description') // Ã Â¦â€¦Ã Â¦Â¥Ã Â¦Â¬Ã Â¦Â¾ content field Ã Â¦Â¯Ã Â¦Â¦Ã Â¦Â¿ Ã Â¦Â¥Ã Â¦Â¾Ã Â¦â€¢Ã Â§â€¡
                    ->first();
    
        if (!$page) {
            return response()->json([
                'status' => 'error',
                'message' => 'Page not found'
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Page fetched successfully',
            'data' => $page
        ]);
    }


    public function userMessage(Request $request)
    {
        // Validation
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        // Save to database
        UserMessage::create($validatedData);

        // Response
        return response()->json([
            'status' => 'success',
            'message' => 'User message saved successfully!',
        ], 201);
    }


    
    public function customerReview(Request $request)
    {
        if ($request->isMethod('get')) {
            $reviews = CustomerReview::get()->map(function ($review) {
                // Decode JSON to array
                $images = json_decode($review->images, true) ?? [];

                // Keep only relative paths
                $review->images = collect($images)->map(function ($img) {
                    // Remove localhost or domain part if exists
                    return str_replace(url('/'), '', $img); // ensures path only
                })->values();

                return $review;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Customer review images fetched successfully',
                'data' => $reviews
            ]);
        }
    }
    
    
    public function productReviewStore(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'name' => 'required|string',
            'comment' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
        ]);
        
        $data['status'] = 0;
        $review = CustomerProductReview::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer Review Stored Successfully',
            'data' => $review,
        ]);
    }


    public function notice()
    {
        $data = Notice::where('status', 1)
            ->select('id', 'title', 'icon', 'phone', 'whatsapp')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Notices fetched successfully',
            'data' => $data
        ]);
    }
    
    public function getDistrict()
    {
        $districts = District::select('district')->distinct()->get();
        return response()->json($districts);
    }
    
    public function districts($district_name)
    {
        $areas = District::whereRaw('LOWER(district) = ?', [strtolower($district_name)])
                        ->pluck('area_name', 'id');

        return response()->json($areas);
    }

    //   Home Page Function End ====================
      public function tagManager(Request $request)
    {
        $data = GoogleTagManager::orderBy('id','DESC')->get();
        return response()->json($data);
    }
      public function productsByTag($tag)
    {
       
        $tagRow = Tag::where('name', $tag)->first();

        if (!$tagRow) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tag not found'
            ], 404);
        }

       
       $products = $tagRow->products()
      ->select( 'slug', 'name', 'old_price', 'new_price','category_id', 'brand_id', 'subcategory_id','childcategory_id')->with('image', 'brand', 'category', 'subcategory', 'childcategory','variables','tags')->get();


        return response()->json([
            'status' => 'success',
            'tag' => $tag,
            'total' => $products->count(),
            'products' => $products
        ]);
    }

    
}
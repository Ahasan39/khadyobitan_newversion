<?php

namespace App\Http\Controllers\Frontend;

use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Product;
use App\Models\District;
use App\Models\CreatePage;
use App\Models\Campaign;
use App\Models\Banner;
use App\Models\ShippingCharge;
use App\Models\Customer;
use App\Models\OrderDetails;
use App\Models\ProductVariable;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Review;
use App\Models\Brand;
use App\Models\GeneralSetting;;
use Cache;
use DB;
use Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use App\Events\ProductViewed;


class FrontendController extends Controller
{
    public function index()
    {
        
        $homeData = Cache::remember('home_page_data', 60 * 60, function() {
            return [
                'sliders' => $this->getSliders(),
                'sliderrightads' => $this->getSliderRightAds(),
                'popup_banner' => $this->getPopupBanner(),
                'hotdeal_top' => $this->getHotDealTop(),
                'new_arrival' => $this->getNewArrival(),
                'top_rated' => $this->getTopRated(),
                'top_selling' => $this->getTopSelling(),
                'homecategory' => $this->getHomeCategory(),
                'brands' => $this->getBrands(),
            ];
        });

       
        $all_products = Product::where('status', 1)
            ->with('image')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->paginate(12);

        return view('frontEnd.layouts.pages.index', array_merge($homeData, [
            'all_products' => $all_products
        ]));
    }



  
   public function loadMoreProducts(Request $request)
    {
        try {
            $page = (int) $request->get('page', 1);
            $perPage = 12;
            
            
            $cacheKey = "products_page_{$page}";
            
            $products = Cache::remember($cacheKey, 60 * 30, function() use ($perPage, $page) {
                return Product::where('status', 1)
                    ->with('image')
                    ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
                    ->withCount('variable as variable_count')
                    ->paginate($perPage, ['*'], 'page', $page);
            });

            
            $productsData = collect($products->items())->map(function($product) {
                return view('frontEnd.layouts.partials.product', ['value' => $product])->render();
            })->toArray();

            return response()->json([
                'success'         => true,
                'products'        => $productsData,
                'has_more_pages'  => $products->hasMorePages(),
                'current_page'    => $products->currentPage(),
                'total_pages'     => $products->lastPage(),
                'total_products'  => $products->total(),
            ]);

        } catch (\Throwable $e) {
            \Log::error('Load more products error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            
            return response()->json([
                'success' => false,
                'error'   => 'Internal server error',
                'message' => config('app.debug') ? $e->getMessage() : 'Something went wrong',
            ], 500);
        }
    }

    
    private function formatProductData($product)
    {
        return [
            'id'           => $product->id,
            'name'         => $product->name,
            'slug'         => $product->slug,
            'new_price'    => $product->new_price,
            'old_price'    => $product->old_price,
            'image'        => $product->image ? $product->image->image : '', 
            'discount'     => $this->calculateDiscount($product->new_price, $product->old_price),
            'has_variants' => $product->variable_count > 0,
            'type'         => $product->type,
            'category_id'  => $product->category_id,
        ];
    }

    
    private function calculateDiscount($newPrice, $oldPrice)
    {
        if ($oldPrice == 0) return 0;
        return round((($oldPrice - $newPrice) / $oldPrice) * 100);
    }

public function loadMoreProductsSimple(Request $request)
{
    try {
        $page = (int) $request->get('page', 1);
        
        // Simple query without relations
        $products = Product::where('status', 1)
            ->select('id', 'name', 'slug', 'new_price', 'old_price')
            ->skip(($page - 1) * 12)
            ->take(12)
            ->get();

        $productsHtml = [];
        foreach ($products as $product) {
            // Simple HTML instead of view
            $productsHtml[] = '<div class="simple-product">' . $product->name . '</div>';
        }

        return response()->json([
            'products' => $productsHtml,
            'has_more_pages' => $products->count() >= 12,
            'page' => $page,
            'count' => count($productsHtml)
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
}


    public function category($slug, Request $request)
    {

        $category = Category::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'category_id' => $category->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')->withCount('variable');
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }
        $products = $products->paginate(30)->withQueryString();
        return view('frontEnd.layouts.pages.category', compact('category', 'products'));
    }

    public function subcategory($slug, Request $request)
    {
        $subcategory = Subcategory::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'subcategory_id' => $subcategory->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id', 'subcategory_id')->withCount('variable');
        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $products = $products->paginate(30)->withQueryString();

        return view('frontEnd.layouts.pages.subcategory', compact('subcategory', 'products'));
    }

    public function products($slug, Request $request)
    {
        $childcategory = Childcategory::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'childcategory_id' => $childcategory->id])->with('category')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id', 'subcategory_id', 'childcategory_id')->withCount('variable');

        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $products = $products->paginate(30)->withQueryString();

        return view('frontEnd.layouts.pages.childcategory', compact('childcategory', 'products'));
    }

    public function brand($slug, Request $request)
    {
        $brand = Brand::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'brand_id' => $brand->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'brand_id')->withCount('variable');
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }
        $products = $products->paginate(30)->withQueryString();
        return view('frontEnd.layouts.pages.brand', compact('brand', 'products'));
    }

    public function bestdeals(Request $request)
    {

        $products = Product::where(['status' => 1, 'topsale' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
            ->withCount('variable');

        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }
        $products = $products->paginate(30)->withQueryString();

        return view('frontEnd.layouts.pages.bestdeals', compact('products'));
    }
    public function newArrival(Request $request)
    {

        $products = Product::where(['status' => 1, 'new_arrival' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
            ->withCount('variable');

        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }
        $products = $products->paginate(30)->withQueryString();

        return view('frontEnd.layouts.pages.bestdeals', compact('products'));
    }
    public function topRated(Request $request)
    {

        $products = Product::where(['status' => 1, 'top_rated' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
            ->withCount('variable');

        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }
        $products = $products->paginate(30)->withQueryString();

        return view('frontEnd.layouts.pages.bestdeals', compact('products'));
    }
    public function topSelling(Request $request)
    {

        $products = Product::where(['status' => 1, 'top_selling' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
            ->withCount('variable');

        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }
        $products = $products->paginate(30)->withQueryString();

        return view('frontEnd.layouts.pages.bestdeals', compact('products'));
    }

public function details($slug)
{
    $details = Cache::remember("product_details_{$slug}", 600, function() use ($slug) {
        return Product::where(['slug' => $slug, 'status' => 1])
            ->with([
                'image',
                'images',
                'category',
                'subcategory',
                'childcategory',
                'variables' => function($q) {
                    $q->where('stock', '>', 0);
                },
                'reviews'
            ])
            ->withCount(['variables as variable_count' => function($q) {
                $q->where('stock', '>', 0);
            }])
            ->firstOrFail();
    });
    

    $products = Cache::remember("related_products_{$details->category_id}", 600, function() use ($details) {
        return Product::where('category_id', $details->category_id)
            ->where('status', 1)
            ->with([
                'image',
                'variables' => function($q) {
                    $q->where('stock', '>', 0);
                }
            ])
            ->withCount(['variables as variable_count' => function($q) {
                $q->where('stock', '>', 0);
            }])
            ->select('id','name','slug','status','category_id','new_price','old_price','type')
            ->take(12)
            ->get();
    });

    $shippingcharge = Cache::remember("shipping_charge_active", 600, fn() => ShippingCharge::where('status', 1)->get());

    $productcolors = Cache::remember("product_colors_{$details->id}", 600, function() use ($details) {
        return ProductVariable::where('product_id', $details->id)
            ->where('stock', '>', 0)
            ->whereNotNull('color')
            ->select('color')
            ->distinct()
            ->get();
    });

    $productsizes = Cache::remember("product_sizes_{$details->id}", 600, function() use ($details) {
        return ProductVariable::where('product_id', $details->id)
            ->where('stock', '>', 0)
            ->whereNotNull('size')
            ->select('size')
            ->distinct()
            ->get();
    });

    $reviews = $details->reviews;



    // Session + Event logic same 
    $sessionData = [
        'cart_items' => session('cart', []),
        'recently_viewed' => session('recently_viewed', []),
        'wishlist' => session('wishlist', []),
        'last_activity' => session('last_activity'),
        'visit_count' => session('visit_count', 0) + 1
    ];
    session(['last_activity' => now(), 'visit_count' => $sessionData['visit_count']]);
    event(new ProductViewed($details, auth()->user(), $sessionData));

    return view('frontEnd.layouts.pages.details', compact(
        'details', 
        'products', 
        'shippingcharge', 
        'reviews', 
        'productcolors', 
        'productsizes'
    ));
}

    public function stock_check(Request $request)
    {
        $product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();

        $status = $product ? true : false;
        $response = [
            'status' => $status,
            'product' => $product
        ];
        return response()->json($response);
    }
    public function quickview(Request $request)
    {
        $data['data'] = Product::where(['id' => $request->id, 'status' => 1])->with('images')->withCount('reviews')->first();
        $data = view('frontEnd.layouts.ajax.quickview', $data)->render();
        if ($data != '') {
            echo $data;
        }
    }
    public function variable_view(Request $request)
    {
        $data['data'] = Product::where(['id' => $request->id, 'status' => 1])
            ->with('image')
            ->first();

        $data['productcolors'] = ProductVariable::where('product_id', $request->id)->where('stock', '>', 0)
            ->whereNotNull('color')
            ->select('color')
            ->distinct()
            ->get();

        $data['productsizes'] = ProductVariable::where('product_id', $request->id)->where('stock', '>', 0)
            ->whereNotNull('size')
            ->select('size')
            ->distinct()
            ->get();
            \Log::info( $data);
        $data = view('frontEnd.layouts.ajax.variableview', $data)->render();
        if ($data != '') {
            echo $data;
        }
    }
    public function livesearch(Request $request)
    {
        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
            ->withCount('variable')
            ->where('status', 1)
            ->with('image');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->get();

        if (empty($request->category) && empty($request->keyword)) {
            $products = [];
        }
        return view('frontEnd.layouts.ajax.search', compact('products'));
    }
    public function search(Request $request)
    {
        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'type')
            ->withCount('variable')
            ->where('status', 1)
            ->with('image');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->paginate(36);
        $keyword = $request->keyword;
        return view('frontEnd.layouts.pages.search', compact('products', 'keyword'));
    }


    public function shipping_charge(Request $request)
    {
        if ($request->id == NULL) {
            Session::put('shipping', 0);
        } else {
            $shipping = ShippingCharge::where(['id' => $request->id])->first();
            Session::put('shipping', $shipping->amount);
        }
        if ($request->campaign == 1) {
            $data = Cart::instance('shopping')->content();
            return view('frontEnd.layouts.ajax.cart_camp', compact('data'));
        }
        return view('frontEnd.layouts.ajax.cart');
    }


    public function contact(Request $request)
    {
        return view('frontEnd.layouts.pages.contact');
    }
    public function mail_send(Request $request)
    {
      

        Toastr::success('Your message send successfully', 'Success!');
        return back();
    }

    public function page($slug)
    {
        $page = CreatePage::where('slug', $slug)->firstOrFail();
        return view('frontEnd.layouts.pages.page', compact('page'));
    }
    public function districts(Request $request)
    {
        $areas = District::where(['district' => $request->id])->pluck('area_name', 'id');
        return response()->json($areas);
    }
    public function campaign($slug, Request $request)
    {
        $campaign = Campaign::where('slug', $slug)->with('images')->first();
        $product = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock')->where(['id' => $campaign->product_id])->first();
        if(!$product) {
            return redirect()->route('home');
        }
        $productcolors = ProductVariable::where('product_id', $campaign->product_id)->where('stock', '>', 0)
            ->whereNotNull('color')
            ->select('color')
            ->distinct()
            ->get();

        $productsizes = ProductVariable::where('product_id', $campaign->product_id)->where('stock', '>', 0)
            ->whereNotNull('size')
            ->select('size')
            ->distinct()
            ->get();

        Cart::instance('shopping')->destroy();

        $var_product = ProductVariable::where(['product_id' => $campaign->product_id])->first();
        if ($product->type == 0) {
            $purchase_price = $var_product ? $var_product->purchase_price : 0;
            $old_price = $var_product ? $var_product->old_price : 0;
            $new_price = $var_product ? $var_product->new_price : 0;
            $stock = $var_product ? $var_product->stock : 0;
        } else {
            $purchase_price = $product->purchase_price;
            $old_price = $product->old_price;
            $new_price = $product->new_price;
            $stock = $product->stock;
        }

        $qty = 1;
        $cartitem = Cart::instance('shopping')->content()->where('id', $product->id)->first();

        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'weight' => 1,
            'price' => $new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $new_price,
                'purchase_price' => $purchase_price,
                'product_size' => $request->product_size,
                'product_color' => $request->product_color,
                'type' => $product->type
            ],
        ]);
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $select_charge = ShippingCharge::where('status', 1)->first();
        Session::put('shipping', $select_charge->amount);
        return view('frontEnd.layouts.pages.campaign.campaign' . $campaign->template, compact('campaign', 'productsizes', 'productcolors', 'shippingcharge', 'old_price', 'new_price'));


    }
    public function campaign_stock(Request $request)
    {
        $product = Product::select('id', 'name', 'slug', 'new_price', 'old_price', 'purchase_price', 'type', 'stock')->where(['id' => $request->id])->first();

        $variable = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();
        $qty = 1;
        $status = $variable ? true : false;

        if ($status == true) {
            Cart::instance('shopping')->destroy();
            Cart::instance('shopping')->add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $qty,
                'weight' => 1,
                'price' => $variable->new_price,
                'options' => [
                    'slug' => $product->slug,
                    'image' => $product->image->image,
                    'old_price' => $variable->new_price,
                    'purchase_price' => $variable->purchase_price,
                    'product_size' => $request->size,
                    'product_color' => $request->color,
                    'type' => $product->type
                ],
            ]);
        }
        $data = Cart::instance('shopping')->content();
        return response()->json($status);
    }

    public function payment_success(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        if ($data[0]->sp_code != 1000) {
            Toastr::error('Your payment failed, try again', 'Oops!');
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }

        if ($data[0]->value1 == 'customer_payment') {

            $customer = Customer::find(Auth::guard('customer')->user()->id);

            // order data save
            $order = new Order();
            $order->invoice_id = $data[0]->id;
            $order->amount = $data[0]->amount;
            $order->customer_id = Auth::guard('customer')->user()->id;
            $order->order_status = $data[0]->bank_status;
            $order->save();

            // payment data save
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = Auth::guard('customer')->user()->id;
            $payment->payment_method = 'shurjopay';
            $payment->amount = $order->amount;
            $payment->trx_id = $data[0]->bank_trx_id;
            $payment->sender_number = $data[0]->phone_no;
            $payment->payment_status = 'paid';
            $payment->save();
            // order details data save
            foreach (Cart::instance('shopping')->content() as $cart) {
                $order_details = new OrderDetails();
                $order_details->order_id = $order->id;
                $order_details->product_id = $cart->id;
                $order_details->product_name = $cart->name;
                $order_details->purchase_price = $cart->options->purchase_price;
                $order_details->sale_price = $cart->price;
                $order_details->qty = $cart->qty;
                $order_details->save();
            }

            Cart::instance('shopping')->destroy();
            Toastr::error('Thanks, Your payment send successfully', 'Success!');
            return redirect()->route('home');
        }

        Toastr::error('Something wrong, please try agian', 'Error!');
        return redirect()->route('home');
    }
    public function payment_cancel(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        Toastr::error('Your payment cancelled', 'Cancelled!');
        if ($data[0]->sp_code != 1000) {
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function product_feed()
    {
        $products = Product::select('id', 'name', 'slug', 'description', 'stock', 'new_price', 'category_id', 'brand_id')
            ->with(['image', 'category', 'brand'])
            ->get();

        $xml = new \SimpleXMLElement('<rss/>');
        $xml->addAttribute('version', '2.0');
        $xml->addAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

        $setting = GeneralSetting::where('status', 1)->first();
        $channel = $xml->addChild('channel');
        $channel->addChild('title', "Webleez Product Feed");
        $channel->addChild('link', 'https://webleez.com');
        $channel->addChild('description', htmlspecialchars($setting->meta_description, ENT_XML1, 'UTF-8'));

        foreach ($products as $product) {
            $item = $channel->addChild('item');
            $item->addChild('g:id', $product->id, 'http://base.google.com/ns/1.0');
            $item->addChild('g:title', htmlspecialchars($product->name, ENT_XML1, 'UTF-8'), 'http://base.google.com/ns/1.0');
            $item->addChild('g:description', htmlspecialchars(strip_tags($product->description), ENT_XML1, 'UTF-8'), 'http://base.google.com/ns/1.0');
            $item->addChild('g:link', url('product/' . $product->slug), 'http://base.google.com/ns/1.0');

            if ($product->category) {
                $item->addChild('g:product_type', htmlspecialchars($product->category->name, ENT_XML1, 'UTF-8'), 'http://base.google.com/ns/1.0');
            }

            if ($product->image) {
                $item->addChild('g:image_link', 'https://webleez.com/' . $product->image->image, 'http://base.google.com/ns/1.0');
            }

            $item->addChild('g:condition', 'new', 'http://base.google.com/ns/1.0');
            $item->addChild('g:availability', $product->stock > 0 ? 'in stock' : 'out of stock', 'http://base.google.com/ns/1.0');
            $item->addChild('g:price', $product->new_price . ' BDT', 'http://base.google.com/ns/1.0');

            if ($product->brand) {
                $item->addChild('g:brand', htmlspecialchars($product->brand->name, ENT_XML1, 'UTF-8'), 'http://base.google.com/ns/1.0');
            }

            $item->addChild('g:identifier_exists', 'yes', 'http://base.google.com/ns/1.0');
            $item->addChild('g:item_group_id', $product->id, 'http://base.google.com/ns/1.0');
            $item->addChild('g:visibility', 'active', 'http://base.google.com/ns/1.0');

            $variantAttribute = $item->addChild('g:additional_variant_attribute', null, 'http://base.google.com/ns/1.0');
            $variantAttribute->addChild('label', 'Size', 'http://base.google.com/ns/1.0');
            $variantAttribute->addChild('value', 'L', 'http://base.google.com/ns/1.0'); // Optional: dynamically assign size
        }

        return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }



    // DB::listen(function ($query) {
    //     Log::channel('test_log')->info('===== started db query ========================================');
    //     Log::channel('test_log')->info(json_encode([
    //         'sql' => $query->sql,
    //         'time' => $query->time . ' ms',
    //         'bindings' => $query->bindings,
    //         'connection' => $query->connection,
    //         'connectionName' => $query->connectionName,
    //     ]));
    // });
    
      private function getSliders()
    {
        return Banner::where(['status' => 1, 'category_id' => 1])
            ->select('id', 'image', 'link')
            ->get();
    }

    private function getSliderRightAds()
    {
        return Banner::where(['status' => 1, 'category_id' => 2])
            ->select('id', 'image', 'link')
            ->limit(2)
            ->get();
    }

    private function getPopupBanner()
    {
        return Banner::where(['status' => 1, 'category_id' => 3])
            ->select('id', 'image', 'link')
            ->first();
    }

    private function getHotDealTop()
    {
        return Product::where(['status' => 1, 'topsale' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->orderBy('id', 'DESC')
            ->withCount('variable')
            ->limit(12)
            ->get();
    }

    private function getNewArrival()
    {
        return Product::where(['status' => 1, 'new_arrival' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->orderBy('id', 'DESC')
            ->withCount('variable')
            ->limit(12)
            ->get();
    }

    private function getTopRated()
    {
        return Product::where(['status' => 1, 'top_rated' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->orderBy('id', 'DESC')
            ->withCount('variable')
            ->limit(12)
            ->get();
    }

    private function getTopSelling()
    {
        return Product::where(['status' => 1, 'top_selling' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->orderBy('id', 'DESC')
            ->withCount('variable')
            ->limit(12)
            ->get();
    }

    private function getHomeCategory()
    {
        return Category::where(['front_view' => 1, 'status' => 1])
            ->select('id', 'name', 'slug', 'front_view', 'status')
            ->orderBy('id', 'ASC')
            ->get();
    }

    private function getBrands()
    {
        return Brand::where(['status' => 1])
            ->select('id', 'name', 'image', 'slug')
            ->orderBy('id', 'ASC')
            ->get();
    }
    
}

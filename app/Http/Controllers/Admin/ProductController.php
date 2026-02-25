<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Brand;
use App\Models\Color;
use App\Models\ProductVariable;
use App\Models\PurchaseDetails;
use App\Models\Size;
use App\Models\Tag;
use File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    public function getSubcategory(Request $request)
    {
        $subcategory = DB::table("subcategories")
            ->where("category_id", $request->category_id)
            ->pluck('name', 'id');
        return response()->json($subcategory);
    }
    public function getChildcategory(Request $request)
    {
        $childcategory = DB::table("childcategories")
            ->where("subcategory_id", $request->subcategory_id)
            ->pluck('name', 'id');
        return response()->json($childcategory);
    }


    public function index(Request $request)
    {
        $data = Product::latest()->select('id', 'name', 'category_id', 'new_price', 'topsale', 'feature_product', 'type', 'status')->with('image', 'category')->withSum('variables', 'stock');
        if ($request->keyword) {
            $data = $data->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        $data = $data->paginate(50);
        return view('backEnd.product.index', compact('data'));
    }
    public function stock_alert()
    {
        $products = Product::select('id', 'name', 'type', 'stock', 'stock_alert')->where('type', 1)->where('stock', '<=', DB::raw('stock_alert'))->with('image')->get();
        $variables = ProductVariable::whereHas('product', function ($query) {
            $query->whereRaw('product_variables.stock <= products.stock_alert');
        })->with('product', 'image')
            ->get();
        return view('backEnd.product.stock_alert', compact('products', 'variables'));
    }

    public function barcode(Request $request)
    {
        // return $request->all();

        if ($request->keyword) {
            $data = Product::select('id', 'name', 'status', 'pro_barcode', 'new_price', 'type')->orderBy('id', 'DESC')->where('name', 'LIKE', '%' . $request->keyword . "%")->with('image', 'variables')->get();
        } else {
            $data = Product::select('id', 'name', 'status', 'pro_barcode', 'new_price', 'type')->orderBy('id', 'DESC')->get();
        }
        // return $data;

        if ($request->product_id && $request->type == 1) {
            $barcode = Product::select('id', 'name', 'slug', 'status', 'pro_barcode', 'new_price', 'type')->where('id', $request->product_id)->first();
            $product = $barcode;

        } elseif ($request->product_id && $request->type == 0) {
            $barcode = ProductVariable::where('id', $request->product_id)->first();
            $product = Product::select('id', 'name', 'slug')->where('id', $barcode->product_id)->first();
        } else {
            $barcode = NULL;
            $product = NULL;
        }
        return view('backEnd.product.barcode', compact('data', 'barcode', 'product'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->select('id', 'name', 'status')->get();
        $brands = Brand::where('status', '1')->select('id', 'name', 'status')->get();
        $colors = Color::where('status', '1')->get();
        $sizes = Size::where('status', '1')->get();
        $all_tags = Tag::get();
        return view('backEnd.product.create', compact('categories', 'brands', 'colors', 'sizes','all_tags'));
    }

   public function store(Request $request)
{
    try {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            
            // Inside Dhaka
            'shipping_title_dhaka' => 'required_with:shipping_amount_dhaka',
            'shipping_amount_dhaka' => 'required_with:shipping_title_dhaka|nullable|numeric|min:0',

            // Outside Dhaka
            'shipping_title_outside_dhaka' => 'required_with:shipping_amount_outside_dhaka',
            'shipping_amount_outside_dhaka' => 'required_with:shipping_title_outside_dhaka|nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        $max_id = DB::table('products')->max('id');
        $max_id = $max_id ? $max_id + 1 : '1';
        $input = $request->except(['image', 'product_type', 'files', 'sizes', 'colors', 'purchase_prices', 'old_prices', 'new_prices', 'stocks', 'images', 'pro_barcodes', 'policy_icon', 'policy_title', 'policy_description', 'tags']);
        $input['slug'] = $this->slug_generate($request->name, $max_id);
        $input['status'] = $request->status ? 1 : 0;
        $input['topsale'] = $request->topsale ? 1 : 0;
        $input['new_arrival'] = $request->new_arrival ? 1 : 0;
        $input['top_rated'] = $request->top_rated ? 1 : 0;
        $input['top_selling'] = $request->top_selling ? 1 : 0;
        
        // Shipping fields
        $input['shipping_charge_dhaka'] = $request->shipping_charge_dhaka;
        $input['shipping_charge_outside_dhaka'] = $request->shipping_charge_outside_dhaka;
        $input['shipping_title_dhaka'] = $request->shipping_title_dhaka;
        $input['shipping_amount_dhaka'] = $request->shipping_amount_dhaka;
        $input['shipping_title_outside_dhaka'] = $request->shipping_title_outside_dhaka;
        $input['shipping_amount_outside_dhaka'] = $request->shipping_amount_outside_dhaka;
        $input['warranty'] = $request->warranty;
        
        if ($request->type == 0) {
            $input['purchase_price'] = $request->purchase_prices[0];
            $input['old_price'] = $request->old_prices[0];
            $input['new_price'] = $request->new_prices[0];
            $input['stock'] = 0;
        } else {
            $input['pro_barcode'] = $request->pro_barcode ?? $this->barcode_generate();
        }
        
        $save_data = Product::create($input);
        // Tag handling
if ($request->tags && is_array($request->tags)) {
    $tagIds = [];
    foreach ($request->tags as $tagName) {
        $tagName = trim($tagName);
        if ($tagName != '') {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
    }
    $save_data->tags()->sync($tagIds); // pivot table-এ save
}

if ($request->policy_title && is_array($request->policy_title)) {
    foreach ($request->policy_title as $index => $title) {
        if (!empty($title)) {
            $save_data->policies()->create([
                'icon'        => $request->policy_icon[$index] ?? null,
                'title'       => $title,
                'description' => $request->policy_description[$index] ?? null,
            ]);
        }
    }
}

        // Product images upload
        $pro_image = $request->file('image');
        
         if ($pro_image) {
                foreach ($pro_image as $key => $image) {
                   
                    $imageUrl = $this->compressAndSaveImage($image, 'product');
                    
                    $pimage = new ProductImage();
                    $pimage->product_id = $save_data->id;
                    $pimage->image = $imageUrl;
                    $pimage->save();
                }
            }
        // if ($pro_image) {
        //     foreach ($pro_image as $key => $image) {
        //         $name = time() . '-' . $image->getClientOriginalName();
        //         $name = strtolower(preg_replace('/\s+/', '-', $name));
        //         $uploadPath = 'public/uploads/product/';
        //         $image->move($uploadPath, $name);
        //         $imageUrl = $uploadPath . $name;
        //         $pimage = new ProductImage();
        //         $pimage->product_id = $save_data->id;
        //         $pimage->image = $imageUrl;
        //         $pimage->save();
        //     }
        // }
        
        // Product variations
        if ($request->stocks) {
            $size = $request->sizes;
            $color = $request->colors;
            $stocks = array_filter($request->stocks);
            $purchase = $request->purchase_prices;
            $old_price = $request->old_prices;
            $new_price = $request->new_prices;
            $pro_barcode = $request->pro_barcodes;
            $images = $request->file('images');

            if (is_array($stocks)) {
                foreach ($stocks as $key => $stock) {
                    $imageUrl = null;

                    if (isset($images[$key]) && $images[$key] != null) {
                        $image = $images[$key];
                        $name = time() . '-' . $image->getClientOriginalName();
                        $name = strtolower(preg_replace('/\s+/', '-', $name));
                        $uploadPath = 'public/uploads/product/';
                        $image->move($uploadPath, $name);
                        $imageUrl = $uploadPath . $name;
                    }

                    if (!empty($size[$key]) || !empty($color[$key])) {
                        $variable = new ProductVariable();
                        $variable->product_id = $save_data->id;
                        $variable->size = $size[$key] ?? null;
                        $variable->color = $color[$key] ?? null;
                        $variable->purchase_price = $purchase[$key] ?? 0;
                        $variable->old_price = $old_price[$key] ?? null;
                        $variable->new_price = $new_price[$key] ?? 0;
                        $variable->pro_barcode = $pro_barcode[$key] ?? $this->barcode_generate();
                        $variable->stock = $stock;
                        $variable->image = $imageUrl;
                        $variable->save();

                        $parchase_var = new PurchaseDetails();
                        $parchase_var->product_id = $save_data->id;
                        $parchase_var->color = $variable->color;
                        $parchase_var->size = $variable->size;
                        $parchase_var->purchase_price = $variable->purchase_price;
                        $parchase_var->old_price = $variable->old_price;
                        $parchase_var->new_price = $variable->new_price;
                        $parchase_var->stock = $variable->stock;
                        $parchase_var->save();
                    }
                }
            }
        }

        // Simple product purchase details
        if ($request->type == 1) {
            $parchase = new PurchaseDetails();
            $parchase->product_id = $save_data->id;
            $parchase->purchase_price = $request->purchase_price;
            $parchase->old_price = $request->old_price;
            $parchase->new_price = $request->new_price;
            $parchase->stock = $request->stock;
            $parchase->save();
        }
        
     

        DB::commit();
        
        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('products.index');
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Validation error  automatically handle 
        throw $e;
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        // Log the error for debugging
        \Log::error('Product Store Error: ' . $e->getMessage());
        
        Toastr::error('Error', 'Failed to save product. Please try again.');
        return redirect()->back()->withInput();
    }
}


    public function edit($id)
    {
        $edit_data = Product::with('images','policies')->find($id);
        $categories = Category::where('status', 1)->select('id', 'name', 'status')->get();
        $subcategory = Subcategory::where('category_id', '=', $edit_data->category_id)->select('id', 'name', 'category_id', 'status')->get();
        $childcategory = Childcategory::where('subcategory_id', '=', $edit_data->subcategory_id)->select('id', 'name', 'subcategory_id', 'status')->get();
        $brands = Brand::where('status', '1')->select('id', 'name', 'status')->get();
        $colors = Color::where('status', '1')->get();
        $sizes = Size::where('status', '1')->get();
          $all_tags = Tag::get();
        $variables = ProductVariable::where('product_id', $id)->get();
        return view('backEnd.product.edit', compact('edit_data', 'categories', 'subcategory', 'childcategory', 'brands', 'sizes', 'colors', 'variables','all_tags'));
    }

  public function update(Request $request)
{
    
    try {
        
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
        ]);

        $update_data = Product::find($request->id);
        
        if (!$update_data) {
            Toastr::error('Error', 'Product not found');
            return redirect()->route('products.index');
        }

        $input = $request->except(['image', 'product_type', 'files', 'sizes', 'colors', 'purchase_prices', 'old_prices', 'new_prices', 'stocks', 'images', 'up_id', 'up_sizes', 'up_colors', 'up_purchase_prices', 'up_old_prices', 'up_new_prices', 'up_stocks', 'up_images', 'pro_barcodes', 'up_pro_barcodes', 'policy_icon', 'policy_title', 'policy_description', 'tags']);
        $input['slug'] = $this->slug_generate($request->name, $update_data->id);
        $input['status'] = $request->status ? 1 : 0;
        $input['topsale'] = $request->topsale ? 1 : 0;
        $update_data->update($input);
        $update_data->shipping_charge_dhaka = $request->shipping_charge_dhaka;
        $update_data->shipping_charge_outside_dhaka = $request->shipping_charge_outside_dhaka;
        $update_data->warranty = $request->warranty;
        $update_data->save();
        $update_data->policies()->delete();
       if ($request->policy_title && is_array($request->policy_title)) {
    foreach ($request->policy_title as $index => $title) {
        if (!empty($title)) {
            $update_data->policies()->create([
                'icon'        => $request->policy_icon[$index] ?? null,
                'title'       => $title,
                'description' => $request->policy_description[$index] ?? null,
            ]);
        }
    }
}

// Tag handling
if ($request->tags && is_array($request->tags)) {
    $tagIds = [];
    foreach ($request->tags as $tagName) {
        $tagName = trim($tagName);
        if ($tagName != '') {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
    }
    $update_data->tags()->sync($tagIds); // pivot table এ save
} else {
    $update_data->tags()->sync([]); // যদি কিছু select না করে, সব detach হবে
}


        // Handle product images
        $images = $request->file('image');
        if ($images) {
            foreach ($images as $key => $image) {
                try {
                    $imageUrl = $this->compressAndSaveImage($image, 'product');

                 

                    $pimage = new ProductImage();
                    $pimage->product_id = $update_data->id;
                    $pimage->image = $imageUrl;
                    $pimage->save();
                } catch (\Exception $e) {
                    \Log::error('Image upload failed: ' . $e->getMessage());
                    continue;
                }
            }
        }

        // Update existing product variables
        if ($request->up_id) {
            $update_ids = array_filter($request->up_id);
            $up_color = $request->up_colors;
            $up_size = $request->up_sizes;
            $up_stock = $request->up_stocks;
            $up_purchase = $request->up_purchase_prices;
            $up_old_price = $request->up_old_prices;
            $up_new_price = $request->up_new_prices;
            $up_pro_barcode = $request->up_pro_barcodes;
            $images = $request->file('up_images');
            
            if ($update_ids) {
                foreach ($update_ids as $key => $update_id) {
                    try {
                        $upvariable = ProductVariable::find($update_id);
                        
                        if (!$upvariable) {
                            \Log::warning('Product variable not found: ' . $update_id);
                            continue;
                        }

                        if (isset($images[$key])) {
                            $image = $images[$key];
                            $name = time() . '-' . $image->getClientOriginalName();
                            $name = strtolower(preg_replace('/\s+/', '-', $name));
                            $uploadPath = 'public/uploads/product/';
                            $image->move($uploadPath, $name);
                            $imageUrl = $uploadPath . $name;
                            
                            if ($upvariable->image && File::exists($upvariable->image)) {
                                File::delete($upvariable->image);
                            }
                        } else {
                            $imageUrl = $upvariable->image;
                        }

                        $upvariable->product_id = $update_data->id;
                        $upvariable->size = $up_size ? $up_size[$key] : NULL;
                        $upvariable->color = $up_color ? $up_color[$key] : NULL;
                        $upvariable->purchase_price = $up_purchase[$key];
                        $upvariable->old_price = $up_old_price ? $up_old_price[$key] : NULL;
                        $upvariable->new_price = $up_new_price[$key];
                        $upvariable->pro_barcode = $up_pro_barcode ? $up_pro_barcode[$key] : NULL;
                        $upvariable->stock = $up_stock[$key];
                        $upvariable->image = $imageUrl;
                        $upvariable->save();
                    } catch (\Exception $e) {
                        \Log::error('Variable update failed for ID ' . $update_id . ': ' . $e->getMessage());
                        continue;
                    }
                }
            }
        }

        // Add new product variables
        if ($request->stocks) {
            $size = $request->sizes;
            $color = $request->colors;
            $stocks = array_filter($request->stocks);
            $purchase = $request->purchase_prices;
            $old_price = $request->old_prices;
            $new_price = $request->new_prices;
            $pro_barcode = $request->pro_barcodes;
            $images = $request->file('images');
            
            if (is_array($stocks)) {
                foreach ($stocks as $key => $stock) {
                    try {
                        if (isset($images[$key])) {
                            $image = $images[$key];
                            $name = time() . '-' . $image->getClientOriginalName();
                            $name = strtolower(preg_replace('/\s+/', '-', $name));
                            $uploadPath = 'public/uploads/product/';
                            $image->move($uploadPath, $name);
                            $imageUrl = $uploadPath . $name;
                        } else {
                            $imageUrl = NULL;
                        }

                        $variable = new ProductVariable();
                        $variable->product_id = $update_data->id;
                        $variable->size = $size ? $size[$key] : NULL;
                        $variable->color = $color ? $color[$key] : NULL;
                        $variable->purchase_price = $purchase[$key];
                        $variable->old_price = $old_price ? $old_price[$key] : NULL;
                        $variable->new_price = $new_price[$key];
                        $variable->stock = $stock;
                        $variable->pro_barcode = $pro_barcode[$key];
                        $variable->image = $imageUrl;
                        $variable->save();
                    } catch (\Exception $e) {
                        \Log::error('New variable creation failed: ' . $e->getMessage());
                        continue;
                    }
                }
            }
        }

        Toastr::success('Success', 'Data updated successfully');
        return redirect()->route('products.index');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Toastr::error('Error', 'Validation failed');
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        \Log::error('Product update failed: ' . $e->getMessage());
        Toastr::error('Error', 'Something went wrong! Please try again.');
        return redirect()->back()->withInput();
    }
}

    public function inactive(Request $request)
    {
        $inactive = Product::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Product::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = Product::find($request->hidden_id);
        foreach ($delete_data->variables as $variable) {
            File::delete($variable->image);
            $variable->delete();
        }
        foreach ($delete_data->images as $pimage) {
            File::delete($pimage->image);
            $pimage->delete();
        }
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
    public function imgdestroy(Request $request)
    {
        $delete_data = ProductImage::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
    public function pricedestroy(Request $request)
    {
        $delete_data = ProductVariable::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success', 'Product price delete successfully');
        return redirect()->back();
    }
    public function update_deals(Request $request)
    {
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['topsale' => $request->status]);
        return response()->json(['status' => 'success', 'message' => 'Hot deals product status change']);
    }
    public function update_feature(Request $request)
    {
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['feature_product' => $request->status]);
        return response()->json(['status' => 'success', 'message' => 'Feature product status change']);
    }
    public function update_status(Request $request)
    {
        $products = Product::whereIn('id', $request->input('product_ids'))->update(['status' => $request->status]);
        return response()->json(['status' => 'success', 'message' => 'Product status change successfully']);
    }
    public function barcode_update(Request $request)
    {
        $products = ProductVariable::whereIn('id', $request->input('product_ids'))->update(['status' => $request->status]);
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }

    public function barcodess(Request $request)
    {
        $products = ProductVariable::get();
        foreach ($products as $product) {
            $product->pro_barcode = str_pad($product->id, 8, '1', STR_PAD_LEFT);
            $product->save();
        }
    }

    public function purchase_list()
    {
        $purchase = PurchaseDetails::with('product')->latest()->paginate(30);
        return view('backEnd.product.purchase_list', compact('purchase'));
    }
    public function purchase_create()
    {
        $data = Product::select('id', 'name', 'status', 'new_price', 'type')->latest()->get();
        return view('backEnd.product.purchase_create', compact('data'));
    }
    public function purchase_store(Request $request)
    {
        $product = Product::select('id', 'name', 'old_price', 'status', 'purchase_price', 'new_price', 'type')->where('id', $request->product_id)->first();
        if ($product) {
            $product = Product::select('id', 'name', 'old_price', 'status', 'purchase_price', 'new_price', 'type')->where('id', $request->product_id)->first();
            $product->stock = +$request->qty;
            $product->save();

            $parchase = new PurchaseDetails();
            $parchase->product_id = $product->id;
            $parchase->purchase_price = $product->purchase_price;
            $parchase->old_price = $product->old_price;
            $parchase->new_price = $product->new_price;
            $parchase->stock = $request->qty;
            $parchase->save();

        } else {
            $product = ProductVariable::where('id', $request->product_id)->first();
            $product->stock = +$request->qty;
            $product->save();

            $parchase = new PurchaseDetails();
            $parchase->product_id = $product->product_id;
            $parchase->color = $product->color;
            $parchase->size = $product->size;
            $parchase->purchase_price = $product->purchase_price;
            $parchase->old_price = $product->old_price;
            $parchase->new_price = $product->new_price;
            $parchase->stock = $request->qty;
            $parchase->save();
        }
        Toastr::success('Success', 'Product purchase successfully');
        return redirect()->back();
    }
    public function purchase_history($id)
    {
        $purchase = PurchaseDetails::where('product_id', $id)->with('product')->latest()->get();
        return view('backEnd.product.purchase_history', compact('purchase'));
    }

    function barcode_generate()
    {
        $max_product = DB::table('products')->max(DB::raw('CAST(pro_barcode AS UNSIGNED)'));
        $max_variable = DB::table('product_variables')->max(DB::raw('CAST(pro_barcode AS UNSIGNED)'));
        $max_barcode = max($max_product, $max_variable);
        return $max_barcode ? $max_barcode + 1 : 100001;
    }

    public function generate_slug(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'id' => 'nullable|integer'
        ]);

        $slug = $this->slug_generate($request->name, $request->id);

        return response()->json(['slug' => $slug]);
    }

    function slug_generate($name, $id = null)
    {
        $slug = Str::slug($name);
        if ($id) {
            $count = Product::where('slug', 'like', $slug . '%')->where('id', '!=', $id)->count();
        } else {
            $count = Product::where('slug', 'like', $slug . '%')->count();
        }
        return $count ? $slug . '-' . ($count + 1) : $slug;
    }
    
    
    public function duplicate($id)
{
    try {
        $product = Product::with('images', 'variables')->findOrFail($id);
        
        // Create new product
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copy)';
        $newProduct->slug = $this->slug_generate($product->name . ' Copy');
        $newProduct->save();
        
        // Duplicate images
        if ($product->images && $product->images->count() > 0) {
            foreach ($product->images as $image) {
                try {
                    $newImage = $image->replicate();
                    $newImage->product_id = $newProduct->id;
                    
                    // Copy image file
                    $oldPath = $image->image;
                    
                    if ($oldPath && File::exists($oldPath)) {
                        $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
                        $newFilename = 'public/uploads/product/' . time() . '-' . uniqid() . '-copy.' . $extension;
                        
                        File::copy($oldPath, $newFilename);
                        $newImage->image = $newFilename;
                    } else {
                        $newImage->image = $oldPath; // Keep original path if file doesn't exist
                        \Log::warning('Image file not found during duplicate: ' . $oldPath);
                    }
                    
                    $newImage->save();
                } catch (\Exception $e) {
                    \Log::error('Failed to duplicate product image: ' . $e->getMessage());
                    // Continue with other images even if one fails
                    continue;
                }
            }
        }
        
        // Duplicate variables if exists
        if ($product->variables && $product->variables->count() > 0) {
            foreach ($product->variables as $variable) {
                try {
                    $newVariable = $variable->replicate();
                    $newVariable->product_id = $newProduct->id;
                    
                    // Copy variable image if exists
                    if ($variable->image) {
                        $oldVarPath = $variable->image;
                        
                        if (File::exists($oldVarPath)) {
                            $varExtension = pathinfo($oldVarPath, PATHINFO_EXTENSION);
                            $newVarFilename = 'public/uploads/product/' . time() . '-' . uniqid() . '-var-copy.' . $varExtension;
                            
                            File::copy($oldVarPath, $newVarFilename);
                            $newVariable->image = $newVarFilename;
                        } else {
                            $newVariable->image = $oldVarPath; // Keep original path if file doesn't exist
                            \Log::warning('Variable image file not found during duplicate: ' . $oldVarPath);
                        }
                    }
                    
                    $newVariable->save();
                } catch (\Exception $e) {
                    \Log::error('Failed to duplicate product variable: ' . $e->getMessage());
                    // Continue with other variables even if one fails
                    continue;
                }
            }
        }
        
        Toastr::success('Success', 'Product duplicated successfully');
        return redirect()->route('products.index');
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        \Log::error('Product not found for duplication. ID: ' . $id);
        Toastr::error('Error', 'Product not found');
        return redirect()->route('products.index');
        
    } catch (\Exception $e) {
        \Log::error('Product duplication failed: ' . $e->getMessage());
        Toastr::error('Error', 'Failed to duplicate product. Please try again.');
        return redirect()->route('products.index');
    }
}




private function compressAndSaveImage($image, $folder = 'product')
{
    try {
        $filename = time() . '-' . uniqid() . '.webp';
        $filename = strtolower(preg_replace('/\s+/', '-', $filename));
        
        $uploadPath = 'public/uploads/' . $folder . '/';
        
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Intervention Image 3.x - Direct usage without provider
        $manager = new ImageManager(new Driver());
        
        $img = $manager->read($image->getRealPath())
            ->resize(700, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->toWebp(75);
        
        // Save the image
        $fullPath = $uploadPath . $filename;
        file_put_contents($fullPath, $img);
        
        return $fullPath;
        
    } catch (\Exception $e) {
        \Log::error('Image Compression Error: ' . $e->getMessage());
        return $this->saveOriginalImage($image, $folder);
    }
}

// Alternative method if above doesn't work
private function compressAndSaveImageV2($image, $folder = 'product')
{
    try {
        $filename = time() . '-' . uniqid() . '.webp';
        $filename = strtolower(preg_replace('/\s+/', '-', $filename));
        
        $uploadPath = 'public/uploads/' . $folder . '/';
        
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $manager = new ImageManager(new Driver());
        $fullPath = $uploadPath . $filename;
        
        $manager->read($image->getRealPath())
            ->resize(700, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($fullPath, 75, 'webp');
        
        return $fullPath;
        
    } catch (\Exception $e) {
        \Log::error('Image Compression Error: ' . $e->getMessage());
        return $this->saveOriginalImage($image, $folder);
    }
}


}

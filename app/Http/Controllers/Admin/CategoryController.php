<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;
use Toastr;
use Image;
use File;
use Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index','store']]);
        $this->middleware('permission:category-create', ['only' => ['create','store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $data = Category::orderBy('id','DESC')->get();
            return view('backEnd.category.index',compact('data'));
        } catch (\Exception $e) {
            \Log::error('Category index failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load categories');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $defaultTags = Tag::all();
            return view('backEnd.category.create', compact('defaultTags'));
        } catch (\Exception $e) {
            \Log::error('Category create page failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load create page');
            return redirect()->route('categories.index');
        }
    }

public function store(Request $request)
{
    try {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $imageUrl = null;
        $bannerUrl = null;

        // Image
        $image = $request->file('image');
        if ($image) {
            $imageUrl = $this->compressAndSaveImage($image, 'category');
        }

        // Banner
        $banner = $request->file('banner');
        if ($banner) {
            $bannerUrl = $this->compressAndSaveBannerImage($banner, 'banner');
        }

        // IMPORTANT: Do NOT use $request->all()
        $input = [
            'name' => $request->name,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->status,
            'slug' => strtolower(preg_replace('/\s+/', '-', $request->name)),
            'front_view' => $request->front_view ? 1 : 0,
            'image' =>$imageUrl,
            'banner' => $bannerUrl,
        ];

        $category = Category::create($input);

        // Tags
        $tags = collect($request->tags ?? [])->map(function ($tag) {
            $slug = str_replace(' ', '-', strtolower($tag));
            return Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $tag]
            )->id;
        });

        if ($tags->count()) {
            $category->tags()->sync($tags);
        }

        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('categories.index');

    } catch (\Exception $e) {
        \Log::error('Category store failed: ' . $e->getMessage());
        Toastr::error('Error', 'Failed to create category');
        return redirect()->back()->withInput();
    }
}


    
    public function edit($id)
    {
        try {
            $edit_data = Category::findOrFail($id);
            $categories = Category::select('id','name')->get();
            $allTags = Tag::get();
            return view('backEnd.category.edit',compact('edit_data','categories','allTags'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Category not found for edit. ID: ' . $id);
            Toastr::error('Error', 'Category not found');
            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            \Log::error('Category edit page failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load edit page');
            return redirect()->route('categories.index');
        }
    }
    
   public function update(Request $request)
{
    try {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $category = Category::findOrFail($request->id);

        // Fixed Input (NO request->all)
        $input = [
            'name' => $request->name,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->status ? 1 : 0,
            'front_view' => $request->front_view ? 1 : 0,
            'slug' => strtolower(preg_replace('/\s+/', '-', $request->name)),
        ];

        // Image
        if ($request->hasFile('image')) {
            try {
                $input['image'] = $this->compressAndSaveImage($request->file('image'), 'category');
            } catch (\Exception $e) {
                \Log::error('Category image update failed: ' . $e->getMessage());
                $input['image'] = $category->image;
            }
        } else {
            $input['image'] = $category->image;
        }

        // Banner
        if ($request->hasFile('banner')) {
            try {
                $input['banner'] = $this->compressAndSaveBannerImage($request->file('banner'), 'banner');
            } catch (\Exception $e) {
                \Log::error('Category banner update failed: ' . $e->getMessage());
                $input['banner'] = $category->banner;
            }
        } else {
            $input['banner'] = $category->banner;
        }

        // Update
        $category->update($input);

        // Tags update
        $tags = collect($request->tags ?? [])->map(function ($tag) {
            $slug = str_replace(' ', '-', strtolower($tag));
            return Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $tag]
            )->id;
        });

        $category->tags()->sync($tags);

        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('categories.index');

    } catch (\Exception $e) {
        \Log::error('Category update failed: ' . $e->getMessage());
        Toastr::error('Error', 'Failed to update category');
        return redirect()->back()->withInput();
    }
}

 
    public function inactive(Request $request)
    {
        try {
            $inactive = Category::findOrFail($request->hidden_id);
            $inactive->status = 0;
            $inactive->save();
            Toastr::success('Success','Data inactive successfully');
            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Category not found for inactive. ID: ' . $request->hidden_id);
            Toastr::error('Error', 'Category not found');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Category inactive failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to inactive category');
            return redirect()->back();
        }
    }

    public function active(Request $request)
    {
        try {
            $active = Category::findOrFail($request->hidden_id);
            $active->status = 1;
            $active->save();
            Toastr::success('Success','Data active successfully');
            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Category not found for active. ID: ' . $request->hidden_id);
            Toastr::error('Error', 'Category not found');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Category active failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to active category');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $category = Category::findOrFail($request->hidden_id);
            
            // Delete subcategories and their child categories
            foreach ($category->subcategories ?? [] as $subcategory) {
                try {
                    foreach ($subcategory->childcategories ?? [] as $childCategory) {
                        $childCategory->delete();
                    }
                    if($subcategory->image && File::exists($subcategory->image)){
                        File::delete($subcategory->image);
                    }
                    $subcategory->delete();
                } catch (\Exception $e) {
                    \Log::error('Subcategory deletion failed: ' . $e->getMessage());
                    continue;
                }
            }

            // Delete products and their related data
            foreach ($category->products ?? [] as $product) {
                try {
                    // Delete product variables
                    foreach ($product->variables ?? [] as $variable) {
                        try {
                            if($variable->image && File::exists($variable->image)){
                                File::delete($variable->image);
                            }
                            $variable->delete();
                        } catch (\Exception $e) {
                            \Log::error('Product variable deletion failed: ' . $e->getMessage());
                            continue;
                        }
                    }

                    // Delete product images
                    foreach ($product->images ?? [] as $image) {
                        try {
                            if($image->image && File::exists($image->image)){
                                File::delete($image->image);
                            }
                            $image->delete();
                        } catch (\Exception $e) {
                            \Log::error('Product image deletion failed: ' . $e->getMessage());
                            continue;
                        }
                    }

                    // Delete product reviews
                    foreach ($product->reviews ?? [] as $review) {
                        try {
                            $review->delete();
                        } catch (\Exception $e) {
                            \Log::error('Product review deletion failed: ' . $e->getMessage());
                            continue;
                        }
                    }

                    // Delete product campaigns
                    foreach ($product->campaigns ?? [] as $campaign) {
                        try {
                            if($product->banner && File::exists($product->banner)){
                                File::delete($product->banner);
                            }
                            $campaign->delete();
                        } catch (\Exception $e) {
                            \Log::error('Product campaign deletion failed: ' . $e->getMessage());
                            continue;
                        }
                    }

                    // Delete product main image
                    if($product->image && File::exists($product->image)){
                        File::delete($product->image);
                    }
                    $product->delete();
                } catch (\Exception $e) {
                    \Log::error('Product deletion failed: ' . $e->getMessage());
                    continue;
                }
            }

            // Delete category image
            if($category->image && File::exists($category->image)){
                File::delete($category->image);
            }
            if($category->banner && File::exists($category->banner)){
                File::delete($category->banner);
            }

            $category->delete();
            Toastr::success('Success','Data delete successfully');
            return redirect()->back();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Category not found for deletion. ID: ' . $request->hidden_id);
            Toastr::error('Error', 'Category not found');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Category deletion failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to delete category. Please try again.');
            return redirect()->back();
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $category = Category::findOrFail($request->id);
            $category->status = $request->status;
            $category->save();
            Toastr::success('Success','Status Changed successfully');
            return response()->json(['success' => true]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Category not found for status update. ID: ' . $request->id);
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Category status update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }
    
    private function compressAndSaveImage($image, $folder = 'category')
    {
   
    try {
        $filename = time() . '-' . uniqid() . '.webp';
        $filename = strtolower(preg_replace('/\s+/', '-', $filename));
        
        $uploadPath = 'public/uploads/category/' ;
       
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Intervention Image 3.x - Direct usage without provider
        $manager = new ImageManager(new Driver());
       
        $img = $manager->read($image->getRealPath())
            ->resize(400, 400, function ($constraint) {
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
    private function compressAndSaveBannerImage($banner, $folder = 'banner')
{
    try {
        $filename = time() . '-' . uniqid() . '.webp';
        $filename = strtolower(preg_replace('/\s+/', '-', $filename));
        
        $uploadPath = 'public/uploads/category/' ;
        
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Intervention Image 3.x - Direct usage without provider
        $manager = new ImageManager(new Driver());
        
        $img = $manager->read($banner->getRealPath())
            ->resize(400, 400, function ($constraint) {
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
        return $this->saveOriginalImage($banner, $folder);
    }
}
}
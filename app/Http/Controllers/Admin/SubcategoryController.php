<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Tag;
use File;
use DB;
use Exception; // Import Exception class

class SubcategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:subcategory-list|subcategory-create|subcategory-edit|subcategory-delete', ['only' => ['index','store']]);
        $this->middleware('permission:subcategory-create', ['only' => ['create','store']]);
        $this->middleware('permission:subcategory-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:subcategory-delete', ['only' => ['destroy']]);
    }

    public function getCategory(Request $request)
    {
        try {
            $category = DB::table("categories")
                ->where("service_category", $request->service_category)
                ->pluck('name', 'id');
            return response()->json($category);
        } catch (Exception $e) {
            Toastr::error('Failed to fetch categories: '.$e->getMessage(), 'Error');
            return response()->json([], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $data = Subcategory::orderBy('id','DESC')
                ->select('id','name','category_id','status')
                ->with('category')
                ->get();
            return view('backEnd.subcategory.index', compact('data'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch subcategories: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $categories = Category::select('id','slug','name','status')->where('status',1)->get();
            $allTags = Tag::all();
            return view('backEnd.subcategory.create', compact('categories','allTags'));
        } catch (Exception $e) {
            Toastr::error('Failed to open create page: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

   public function store(Request $request)
{
    try {
        $this->validate($request, [
            'category_id' => 'required',
            'name'        => 'required',
            'status'      => 'required',
        ]);

        // Image upload
        $imageUrl = null;
        if ($request->file('image')) {
            $image = $request->file('image');
            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/subcategory/';
            $imageUrl = $uploadpath . $name;

            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $img->resize(null, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imageUrl);
        }

        
        $input = $request->except('tags');
        $input['slug']  = strtolower(preg_replace('/\s+/', '-', $request->name));
        $input['slug']  = str_replace('/', '', $input['slug']);
        $input['image'] = $imageUrl;

        // Create Subcategory
        $subcategory = Subcategory::create($input);

        // ===== TAGS PART =====
        if ($request->filled('tags')) {
            $tags = collect($request->tags)->map(function ($tag) {
                $slug = str_replace(' ', '-', strtolower($tag));
                return Tag::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $tag]
                )->id;
            });

            $subcategory->tags()->sync($tags);
        }
       

        Toastr::success('Data inserted successfully', 'Success');
        return redirect()->route('subcategories.index');

    } catch (Exception $e) {
        Toastr::error('Failed to insert subcategory: ' . $e->getMessage(), 'Error');
        return redirect()->back()->withInput();
    }
}



    public function edit($id)
    {
        try {
            $edit_data = Subcategory::findOrFail($id);
            $categories = Category::select('id','slug','name','status')->where('status',1)->get();
            $allTags = Tag::get();
            return view('backEnd.subcategory.edit', compact('edit_data','categories','allTags'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch subcategory for edit: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
{
    try {
        $this->validate($request, [
            'category_id' => 'required',
            'name' => 'required',
        ]);

        $update_data = Subcategory::findOrFail($request->id);

        $input = $request->except('image', 'tags');

       

        // Image handle
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $name = time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/subcategory/';
            $imageUrl = $uploadpath . $name;

            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);

            $img->resize(null, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->save($imageUrl);

            // delete old
            if (File::exists($update_data->image)) {
                File::delete($update_data->image);
            }

            $input['image'] = $imageUrl;
        } else {
            $input['image'] = $update_data->image;
        }

        // slug
        $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
        $input['slug'] = str_replace('/', '', $input['slug']);

        // status
        $input['status'] = $request->status ? 1 : 0;

        $update_data->update($input);
    // Tags update
        $tags = collect($request->tags ?? [])->map(function ($tag) {
            $slug = str_replace(' ', '-', strtolower($tag));
            return Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $tag]
            )->id;
        });

        $update_data->tags()->sync($tags);
        Toastr::success('Subcategory updated successfully', 'Success');
        return redirect()->route('subcategories.index');

    } catch (\Exception $e) {
        Toastr::error('Failed to update subcategory: ' . $e->getMessage(), 'Error');
        return redirect()->back()->withInput();
    }
}


    public function inactive(Request $request)
    {
        try {
            $inactive = Subcategory::findOrFail($request->hidden_id);
            $inactive->status = 0;
            $inactive->save();
            Toastr::success('Data marked as inactive successfully', 'Success');
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error('Failed to mark inactive: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function active(Request $request)
    {
        try {
            $active = Subcategory::findOrFail($request->hidden_id);
            $active->status = 1;
            $active->save();
            Toastr::success('Data marked as active successfully', 'Success');
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error('Failed to mark active: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $subcategory = Subcategory::findOrFail($request->hidden_id);

            // Delete child categories if exist
            foreach ($subcategory->childcategories ?? [] as $childCategory) {
                $childCategory->delete();
            }

            // Delete related products and their relations
            foreach ($subcategory->products ?? [] as $product) {
                foreach ($product->variables ?? [] as $variable) {
                    File::delete($variable->image);
                    $variable->delete();
                }
                foreach ($product->images ?? [] as $image) {
                    File::delete($image->image);
                    $image->delete();
                }
                foreach ($product->reviews ?? [] as $review) {
                    $review->delete();
                }
                foreach ($product->campaigns ?? [] as $campaign) {
                    File::delete($product->banner);
                    $campaign->delete();
                }
                File::delete($product->image);
                $product->delete();
            }

            File::delete($subcategory->image);
            $subcategory->delete();

            Toastr::success('Data deleted successfully', 'Success');
            return redirect()->back();

        } catch (Exception $e) {
            Toastr::error('Failed to delete subcategory: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
}

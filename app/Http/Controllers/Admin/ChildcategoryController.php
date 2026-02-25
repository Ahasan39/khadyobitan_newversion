<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Childcategory;
use App\Models\Subcategory;
use App\Models\Tag;
use Toastr;
use Image;
use File;
use Str;
use DB;

class ChildcategoryController extends Controller
{
    public function getSubCategory(Request $request)
    {
        try {
            $category = DB::table("subcategories")
                ->where("subcategorytype", $request->childcategorytype)
                ->pluck('name', 'id');
            return response()->json($category);
        } catch (\Exception $e) {
            \Log::error('Get subcategory failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch subcategories'], 500);
        }
    }

    function __construct()
    {
        $this->middleware('permission:childcategory-list|childcategory-create|childcategory-edit|childcategory-delete', ['only' => ['index','store']]);
        $this->middleware('permission:childcategory-create', ['only' => ['create','store']]);
        $this->middleware('permission:childcategory-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:childcategory-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $data = Childcategory::orderBy('id','DESC')->with('subcategory')->get();
            return view('backEnd.childcategory.index', compact('data'));
        } catch (\Exception $e) {
            \Log::error('Childcategory index failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load child categories');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
             $defaultTags = Tag::all();
            return view('backEnd.childcategory.create',  compact('defaultTags'));
        } catch (\Exception $e) {
            \Log::error('Childcategory create page failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load create page');
            return redirect()->route('childcategories.index');
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'subcategory_id' => 'required',
                'name' => 'required',
                'status' => 'required',
            ]);
            
            $input = $request->except('tags');
            $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
            $input['slug'] = str_replace('/', '', $input['slug']);
            
            $category = Childcategory::create($input);
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
            Toastr::success('Success','Data insert successfully');
            return redirect()->route('childcategories.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::error('Error', 'Validation failed');
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Childcategory store failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to create child category. Please try again.');
            return redirect()->back()->withInput();
        }
    }
    
    public function edit($id)
    {
        try {
            $edit_data = Childcategory::findOrFail($id);
            $categories = Subcategory::select('id','name')->get();
             $allTags = Tag::get();
            return view('backEnd.childcategory.edit',compact('edit_data','categories','allTags'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Childcategory not found for edit. ID: ' . $id);
            Toastr::error('Error', 'Child category not found');
            return redirect()->route('childcategories.index');
        } catch (\Exception $e) {
            \Log::error('Childcategory edit page failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load edit page');
            return redirect()->route('childcategories.index');
        }
    }
    
    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'subcategory_id' => 'required',
                'name' => 'required',
                'status' => 'required',
            ]);

            $update_data = Childcategory::findOrFail($request->id);
            $input = $request->except('tags');
            
            $input['slug'] = strtolower(preg_replace('/\s+/', '-', $request->name));
            $input['slug'] = str_replace('/', '', $input['slug']);
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
            Toastr::success('Success','Data update successfully');
            return redirect()->route('childcategories.index');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Childcategory not found for update. ID: ' . $request->id);
            Toastr::error('Error', 'Child category not found');
            return redirect()->route('childcategories.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::error('Error', 'Validation failed');
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Childcategory update failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to update child category. Please try again.');
            return redirect()->back()->withInput();
        }
    }
 
    public function inactive(Request $request)
    {
        try {
            $inactive = Childcategory::findOrFail($request->hidden_id);
            $inactive->status = 0;
            $inactive->save();
            Toastr::success('Success','Data inactive successfully');
            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Childcategory not found for inactive. ID: ' . $request->hidden_id);
            Toastr::error('Error', 'Child category not found');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Childcategory inactive failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to inactive child category');
            return redirect()->back();
        }
    }

    public function active(Request $request)
    {
        try {
            $active = Childcategory::findOrFail($request->hidden_id);
            $active->status = 1;
            $active->save();
            Toastr::success('Success','Data active successfully');
            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Childcategory not found for active. ID: ' . $request->hidden_id);
            Toastr::error('Error', 'Child category not found');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Childcategory active failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to active child category');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $childcategory = Childcategory::findOrFail($request->hidden_id);
            
            // Delete products and their related data
            foreach ($childcategory->products ?? [] as $product) {
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

            // Delete childcategory image
            if($childcategory->image && File::exists($childcategory->image)){
                File::delete($childcategory->image);
            }
            
            $childcategory->delete();

            Toastr::success('Success','Data delete successfully');
            return redirect()->back();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Childcategory not found for deletion. ID: ' . $request->hidden_id);
            Toastr::error('Error', 'Child category not found');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Childcategory deletion failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to delete child category. Please try again.');
            return redirect()->back();
        }
    }
}
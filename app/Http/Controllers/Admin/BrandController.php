<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Image;
use File;
use Toastr;
use Exception;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BrandController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:brand-list|brand-create|brand-edit|brand-delete', ['only' => ['index','store']]);
        $this->middleware('permission:brand-create', ['only' => ['create','store']]);
        $this->middleware('permission:brand-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:brand-delete', ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        try {
            $data = Brand::orderBy('id','DESC')->get();
            return view('backEnd.brand.index', compact('data'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch brands: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            return view('backEnd.brand.create');
        } catch (Exception $e) {
            Toastr::error('Failed to open create page: '.$e->getMessage(), 'Error');
            return redirect()->back();
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
            $image = $request->file('image');
            if ($image) {
                  $imageUrl = $this->compressAndSaveImage($image, 'brand');
               
            }

            $input = $request->all();
            $input['slug'] = strtolower(preg_replace('/\s+/u', '-', trim($request->name)));
            $input['image'] = $imageUrl;

            Brand::create($input);
            Toastr::success('Data inserted successfully', 'Success');
            return redirect()->route('brands.index');

        } catch (Exception $e) {
            Toastr::error('Failed to insert brand: '.$e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $edit_data = Brand::findOrFail($id);
            return view('backEnd.brand.edit', compact('edit_data'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch brand for edit: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
            ]);

            $update_data = Brand::findOrFail($request->id);
            $input = $request->all();

            $image = $request->file('image');
            if ($image) {
               $imageUrl = $this->compressAndSaveImage($image, 'brand');
                $input['image'] = $imageUrl;
                File::delete($update_data->image);
            } else {
                $input['image'] = $update_data->image;
            }

            $input['status'] = $request->status ? 1 : 0;
            $update_data->update($input);

            Toastr::success('Data updated successfully', 'Success');
            return redirect()->route('brands.index');

        } catch (Exception $e) {
            Toastr::error('Failed to update brand: '.$e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function inactive(Request $request)
    {
        try {
            $inactive = Brand::findOrFail($request->hidden_id);
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
            $active = Brand::findOrFail($request->hidden_id);
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
            $delete_data = Brand::findOrFail($request->hidden_id);
            File::delete($delete_data->image); // delete image file
            $delete_data->delete();
            Toastr::success('Data deleted successfully', 'Success');
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error('Failed to delete brand: '.$e->getMessage(), 'Error');
            return redirect()->back();
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
            ->resize(210, 210, function ($constraint) {
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

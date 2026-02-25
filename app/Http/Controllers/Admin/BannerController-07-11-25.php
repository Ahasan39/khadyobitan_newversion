<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerCategory;
use App\Models\Banner;
use Toastr;
use Image;
use File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception; // Import Exception class

class BannerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:banner-list|banner-create|banner-edit|banner-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:banner-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:banner-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:banner-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $data = Banner::orderBy('id', 'DESC')->with('category')->get();
            return view('backEnd.banner.index', compact('data'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch banners: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $bcategories = BannerCategory::orderBy('id', 'DESC')->select('id', 'name')->get();
            return view('backEnd.banner.create', compact('bcategories'));
        } catch (Exception $e) {
            Toastr::error('Failed to open create page: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'link' => 'required',
                'status' => 'required',
            ]);

            $fileUrl = null;
            $file = $request->file('image');
            if ($file) {
                $imageUrl = $this->compressAndSaveImage($file, 'banner');
                    
            }

            $input = $request->all();
            $input['status'] = $request->status ? 1 : 0;
            $input['image'] =  $imageUrl;

            Banner::create($input);
            Toastr::success('Data inserted successfully', 'Success');
            return redirect()->route('banners.index');

        } catch (Exception $e) {
            Toastr::error('Failed to insert banner: '.$e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $edit_data = Banner::findOrFail($id);
            $bcategories = BannerCategory::select('id', 'name')->get();
            return view('backEnd.banner.edit', compact('edit_data', 'bcategories'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch banner for edit: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'link' => 'required',
            ]);

            $update_data = Banner::findOrFail($request->id);
            $input = $request->all();

            $file = $request->file('image');
            if ($file) {
                 $imageUrl = $this->compressAndSaveImage($file, 'banner');
                $input['image'] = $imageUrl;

                // delete old image
                File::delete($update_data->image);
            } else {
                $input['image'] = $update_data->image;
            }

            $input['status'] = $request->status ? 1 : 0;
            $update_data->update($input);

            Toastr::success('Data updated successfully', 'Success');
            return redirect()->route('banners.index');

        } catch (Exception $e) {
            Toastr::error('Failed to update banner: '.$e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function inactive(Request $request)
    {
        try {
            $inactive = Banner::findOrFail($request->hidden_id);
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
            $active = Banner::findOrFail($request->hidden_id);
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
            $delete_data = Banner::findOrFail($request->hidden_id);

            // Delete banner image file
            File::delete($delete_data->image);

            $delete_data->delete();
            Toastr::success('Data deleted successfully', 'Success');
            return redirect()->back();

        } catch (Exception $e) {
            Toastr::error('Failed to delete banner: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
    
     private function compressAndSaveImage($file, $folder = 'banner')
    {
   
    try {
        $filename = time() . '-' . uniqid() . '.webp';
        $filename = strtolower(preg_replace('/\s+/', '-', $filename));
        
        $uploadPath = 'public/uploads/banner/' ;
       
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Intervention Image 3.x - Direct usage without provider
        $manager = new ImageManager(new Driver());
       
        $img = $manager->read($file->getRealPath())
            ->resize(1200, 700, function ($constraint) {
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
        return $this->saveOriginalImage($file, $folder);
    }
}
    
}

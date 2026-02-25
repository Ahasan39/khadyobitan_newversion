<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Toastr;
use Image;
use File;
use Exception;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class GeneralSettingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:setting-list|setting-create|setting-edit|setting-delete', ['only' => ['index','store']]);
        $this->middleware('permission:setting-create', ['only' => ['create','store']]);
        $this->middleware('permission:setting-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:setting-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $show_data = GeneralSetting::orderBy('id','DESC')->get();
            return view('backEnd.settings.index',compact('show_data'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch settings: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            return view('backEnd.settings.create');
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
            'white_logo' => 'required|image',
            'favicon' => 'required|image',
            'status' => 'required',
        ]);

        $uploadPath = 'public/uploads/settings/';

        // === Compress and save each image ===
        $white_logo = $request->hasFile('white_logo')
            ? $this->compressAndSaveImage($request->file('white_logo'), 'settings')
            : null;

        $dark_logo = $request->hasFile('dark_logo')
            ? $this->compressAndSaveImage($request->file('dark_logo'), 'settings')
            : null;

        $favicon = $request->hasFile('favicon')
            ? $this->compressAndSaveImage($request->file('favicon'), 'settings')
            : null;

        // === Prepare data for insertion ===
        $input = $request->except(['white_logo', 'dark_logo', 'favicon']);
        $input['white_logo'] = $white_logo;
        $input['dark_logo'] = $dark_logo;
        $input['favicon'] = $favicon;

        // === Save to DB ===
        GeneralSetting::create($input);

        Toastr::success('Data inserted successfully', 'Success');
        return redirect()->route('settings.index');

    } catch (Exception $e) {
        \Log::error('General Setting Store Error: ' . $e->getMessage());
        Toastr::error('Failed to insert setting: ' . $e->getMessage(), 'Error');
        return redirect()->back()->withInput();
    }
}


    public function edit($id)
    {
        try {
            $edit_data = GeneralSetting::findOrFail($id);
            return view('backEnd.settings.edit',compact('edit_data'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch setting for edit: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

 public function update(Request $request)
{
    try {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $update_data = GeneralSetting::findOrFail($request->id);
        $input = $request->except(['white_logo', 'dark_logo', 'favicon']);

        // === White Logo ===
        if ($request->hasFile('white_logo')) {
            if (!empty($update_data->white_logo) && file_exists($update_data->white_logo)) {
                File::delete($update_data->white_logo);
            }
            $input['white_logo'] = $this->compressAndSaveImage($request->file('white_logo'), 'settings', 400, 400);
        } else {
            $input['white_logo'] = $update_data->white_logo;
        }

        // === Dark Logo ===
        if ($request->hasFile('dark_logo')) {
            if (!empty($update_data->dark_logo) && file_exists($update_data->dark_logo)) {
                File::delete($update_data->dark_logo);
            }
            $input['dark_logo'] = $this->compressAndSaveImage($request->file('dark_logo'), 'settings', 400, 400);
        } else {
            $input['dark_logo'] = $update_data->dark_logo;
        }

        // === Favicon ===
        if ($request->hasFile('favicon')) {
            if (!empty($update_data->favicon) && file_exists($update_data->favicon)) {
                File::delete($update_data->favicon);
            }
            $input['favicon'] = $this->compressAndSaveImage($request->file('favicon'), 'settings', 128, 128);
        } else {
            $input['favicon'] = $update_data->favicon;
        }

        // === Status ===
        $input['status'] = $request->status ? 1 : 0;

        // === Update database ===
        $update_data->update($input);

        Toastr::success('Data updated successfully', 'Success');
        return redirect()->route('settings.index');

    } catch (Exception $e) {
        \Log::error('General Setting Update Error: ' . $e->getMessage());
        Toastr::error('Failed to update setting: ' . $e->getMessage(), 'Error');
        return redirect()->back()->withInput();
    }
}


    public function inactive(Request $request)
    {
        try {
            $inactive = GeneralSetting::findOrFail($request->hidden_id);
            $inactive->status = 0;
            $inactive->save();
            Toastr::success('Data marked as inactive', 'Success');
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error('Failed to mark inactive: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function active(Request $request)
    {
        try {
            $active = GeneralSetting::findOrFail($request->hidden_id);
            $active->status = 1;
            $active->save();
            Toastr::success('Data marked as active', 'Success');
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error('Failed to mark active: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $delete_data = GeneralSetting::findOrFail($request->hidden_id);
            // Delete images
            File::delete([$delete_data->white_logo, $delete_data->dark_logo, $delete_data->favicon]);
            $delete_data->delete();
            Toastr::success('Data deleted successfully', 'Success');
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error('Failed to delete setting: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    // Helper function to process and save image
    private function processImage($image, $uploadPath)
    {
        $name = time() . '-' . $image->getClientOriginalName();
        $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
        $name = strtolower(preg_replace('/\s+/', '-', $name));
        $imageUrl = $uploadPath . $name;

        $img = Image::make($image->getRealPath());
        $img->encode('webp', 90);
        $width = '';
        $height = '';
        $img->height() > $img->width() ? $width=null : $height=null;
        $img->resize($width, $height);
        $img->save($imageUrl);

        return $imageUrl;
    }
    
    
    private function compressAndSaveImage($file, $folder = 'uploads')
{
    try {
        $filename = time() . '-' . uniqid() . '.webp';
        $filename = strtolower(preg_replace('/\s+/', '-', $filename));

        $uploadPath = 'public/uploads/' . $folder . '/';
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Use Intervention Image for compression
        $manager = new ImageManager(new Driver());
        $img = $manager->read($file->getRealPath())
            ->resize(220, 80, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->toWebp(80);

        $fullPath = $uploadPath . $filename;
        file_put_contents($fullPath, $img);

        return $fullPath;
    } catch (\Exception $e) {
        \Log::error('Image Compression Error: ' . $e->getMessage());
        return $this->saveOriginalImage($file, $folder);
    }
}

private function saveOriginalImage($file, $folder)
{
    $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
    $filename = strtolower(preg_replace('/\s+/', '-', $filename));

    $uploadPath = 'public/uploads/' . $folder . '/';
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $file->move($uploadPath, $filename);
    return $uploadPath . $filename;
}

}

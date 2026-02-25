<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CampaignReview;
use App\Models\Campaign;
use Image;
use Toastr;
use Str;
use File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CampaignController extends Controller
{
     function __construct()
    {
         $this->middleware('permission:campaign-list|campaign-create|campaign-edit|campaign-delete', ['only' => ['index','store']]);
         $this->middleware('permission:campaign-create', ['only' => ['create','store']]);
         $this->middleware('permission:campaign-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:campaign-delete', ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        $show_data = Campaign::orderBy('id','DESC')->get();
        return view('backEnd.campaign.index',compact('show_data'));
    }
    public function create()
    {
        $products = Product::where(['status'=>1])->select('id','name','status')->get();
        return view('backEnd.campaign.create',compact('products'));
    }
    public function store(Request $request)
{
    $this->validate($request, [
        'short_description' => 'required',
        'description' => 'required',
        'name' => 'required',
        'status' => 'required',
    ]);

    $input = $request->except(['files', 'image', 'banner']);

    // Compress and save banner image
    $bannerFile = $request->file('banner');
    if ($bannerFile) {
        $input['banner'] = $this->compressAndSaveImage($bannerFile, 'campaign');
    }

    // Generate slug
    $input['slug'] = strtolower(Str::slug($request->name));

    // Create campaign
    $campaign = Campaign::create($input);

    // Multiple images (for CampaignReview)
    $images = $request->file('image');
    if ($images) {
        foreach ($images as $key => $file) {
            $imageUrl = $this->compressAndSaveImage($file, 'campaign');

            $pimage = new CampaignReview();
            $pimage->campaign_id = $campaign->id;
            $pimage->image = $imageUrl;
            $pimage->save();
        }
    }

    Toastr::success('Success', 'Data inserted successfully');
    return redirect()->route('campaign.index');
}
    
    public function edit($id)
    {
        $edit_data = Campaign::with('images')->find($id);
        $select_products = Product::where('campaign_id',$id)->get();
        $show_data = Campaign::orderBy('id','DESC')->get();
        $products = Product::where(['status'=>1])->select('id','name','status')->get();
        return view('backEnd.campaign.edit',compact('edit_data','products','select_products'));
    }
    
 public function update(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'short_description' => 'required',
        'description' => 'required',
        'status' => 'required',
    ]);

    $update_data = Campaign::findOrFail($request->hidden_id);

    $input = $request->except('hidden_id', 'product_ids', 'files', 'image', 'banner');

    // === Banner update ===
    if ($request->hasFile('banner')) {
        // Delete old banner if exists
        if (!empty($update_data->banner) && file_exists($update_data->banner)) {
            File::delete($update_data->banner);
        }

        // Compress and save new banner
        $input['banner'] = $this->compressAndSaveImage($request->file('banner'), 'campaign');
    } else {
        $input['banner'] = $update_data->banner;
    }

    // Update slug
    $input['slug'] = strtolower(Str::slug($request->name));

    // Update main campaign data
    $update_data->update($input);

    // === Multiple image uploads (CampaignReview) ===
    $images = $request->file('image');
    if ($images) {
        foreach ($images as $key => $file) {
            $imageUrl = $this->compressAndSaveImage($file, 'campaign');

            $pimage = new CampaignReview();
            $pimage->campaign_id = $update_data->id;
            $pimage->image = $imageUrl;
            $pimage->save();
        }
    }

    Toastr::success('Success', 'Data updated successfully');
    return redirect()->route('campaign.index');
}

 
    public function inactive(Request $request)
    {
        $inactive = Campaign::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Campaign::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
       
        $delete_data = Campaign::find($request->hidden_id);
        $delete_data->delete();
        
        $campaign = Product::whereNotNull('campaign_id')->get();
        foreach($campaign as $key=>$value){
            $product = Product::find($value->id);
            $product->campaign_id = null;
            $product->save();
        }
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
    public function duplicate(Request $request)
    { 
        $data = Campaign::find($request->hidden_id);
        $newData = $data->replicate();
        $newData->save();
        Toastr::success('Success','Campaign duplicate successfully');
        return redirect()->back();
    } 
    public function imgdestroy(Request $request)
    { 
        $delete_data = CampaignReview::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    } 
    
    private function compressAndSaveImage($file, $folder = 'campaign')
{
    try {
        $filename = time() . '-' . uniqid() . '.webp';
        $filename = strtolower(preg_replace('/\s+/', '-', $filename));

        $uploadPath = 'public/uploads/' . $folder . '/';

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Use Intervention Image to compress
        $manager = new ImageManager(new Driver());
        $img = $manager->read($file->getRealPath())
            ->resize(1200, 700, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->toWebp(75);

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

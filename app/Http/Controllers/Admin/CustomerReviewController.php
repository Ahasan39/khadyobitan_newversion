<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerReview;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use App\Models\CustomerProductReview;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class CustomerReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerReviw = CustomerReview::all();
        return view('backEnd.customer_review.index', compact('customerReviw'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backEnd.customer_review.create');
    }

    /**
     * Store a newly created resource in storage.
     */
  
   public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
        ]);
    
        $imagePaths = [];
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageUrl = $this->compressAndSaveImage($image, 'product');
                $imagePaths[] = $imageUrl;
            }
        }
    
        $review = new CustomerReview();
        $review->images = json_encode($imagePaths); // সবগুলো image একসাথে save
        $review->save();
    
        Toastr::success('Success', 'Customer Review stored successfully');
        return redirect()->route('customer_reviews.index');
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


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customerReviw = CustomerReview::findOrFail($id);
        return view('backEnd.customer_review.edit', compact('customerReviw'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
    {
        $customerReview = CustomerReview::findOrFail($id);
    
        // Handle image upload
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            
            // Delete old image if exists
            if ($customerReview->images && Storage::exists($customerReview->images)) {
                Storage::delete($customerReview->images);
            }
            
            // Upload and compress new image
            $imageUrl = $this->compressAndSaveImage($images[0], 'product');
            $customerReview->images = $imageUrl;
        }
    
        $customerReview->save();
    
        Toastr::success('Success', 'Customer Review updated successfully');
        return redirect()->route('customer_reviews.index');
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
    {
        // Find the review
        $review = CustomerReview::findOrFail($id);

        // Decode JSON images to array
        $images = json_decode($review->images, true);

        // Delete all associated images
        if (!empty($images) && is_array($images)) {
            foreach ($images as $img) {
                $path = public_path($img);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        // Delete the review itself
        $review->delete();

        Toastr::success('Success', 'Customer Review deleted successfully');
        return redirect()->route('customer_reviews.index');
    }

    public function productReviewManage()
    {
       $customerReview = CustomerProductReview::with('product')->OrderBy('id', 'desc')->get();
       return view('backEnd.customer_review.productReview', compact('customerReview')); 
    }
    
    public function reviewDelete($id)
    {
         // Find the review
        $customerReview = CustomerProductReview::findOrFail($id);
        $customerReview->delete();

        Toastr::success('Success','Customer Review Delete successfully');
        return redirect()->route('product_reviews.manage');
    }
    
    public function inactive(Request $request)
    {
        $inactive = CustomerProductReview::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Review inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = CustomerProductReview::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Review active successfully');
        return redirect()->back();
    }
    
}
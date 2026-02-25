<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerCategory;
use Toastr;
use Exception; // Import Exception class

class BannerCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:banner-category-list|banner-category-create|banner-category-edit|banner-category-delete', ['only' => ['index','store']]);
        $this->middleware('permission:banner-category-create', ['only' => ['create','store']]);
        $this->middleware('permission:banner-category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:banner-category-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $data = BannerCategory::orderBy('id','DESC')->get();
            return view('backEnd.banner.category.index', compact('data'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch banner categories: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $categories = BannerCategory::orderBy('id','DESC')->select('id','name')->get();
            return view('backEnd.banner.category.create', compact('categories'));
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

            $input = $request->all();
            BannerCategory::create($input);

            Toastr::success('Data inserted successfully', 'Success');
            return redirect()->route('banner_category.index');

        } catch (Exception $e) {
            Toastr::error('Failed to insert banner category: '.$e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $edit_data = BannerCategory::findOrFail($id);
            return view('backEnd.banner.category.edit', compact('edit_data'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch banner category for edit: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
            ]);

            $update_data = BannerCategory::findOrFail($request->id);
            $input = $request->all();
            $input['status'] = $request->status ? 1 : 0;
            $update_data->update($input);

            Toastr::success('Data updated successfully', 'Success');
            return redirect()->route('banner_category.index');

        } catch (Exception $e) {
            Toastr::error('Failed to update banner category: '.$e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function inactive(Request $request)
    {
        try {
            $inactive = BannerCategory::findOrFail($request->hidden_id);
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
            $active = BannerCategory::findOrFail($request->hidden_id);
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
            $delete_data = BannerCategory::findOrFail($request->hidden_id);
            $delete_data->delete();

            Toastr::success('Data deleted successfully', 'Success');
            return redirect()->back();

        } catch (Exception $e) {
            Toastr::error('Failed to delete banner category: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
}

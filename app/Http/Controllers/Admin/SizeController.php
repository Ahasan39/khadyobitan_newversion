<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use Toastr;
use Exception; // Import Exception class

class SizeController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:size-list|size-create|size-edit|size-delete', ['only' => ['index','show']]);
        $this->middleware('permission:size-create', ['only' => ['create','store']]);
        $this->middleware('permission:size-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:size-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $show_data = Size::orderBy('id','DESC')->get();
            return view('backEnd.size.index', compact('show_data'));
        } catch (Exception $e) {
            // Handle error and show message
            Toastr::error('Failed to fetch data: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            return view('backEnd.size.create');
        } catch (Exception $e) {
            Toastr::error('Failed to open create page: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'status' => 'required',
            ]);

            $input = $request->all();
            Size::create($input);        

            Toastr::success('Data inserted successfully', 'Success');
            return redirect()->route('sizes.index');

        } catch (Exception $e) {
            Toastr::error('Failed to insert data: '.$e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $edit_data = Size::findOrFail($id); // Using findOrFail for better error handling
            return view('backEnd.size.edit', compact('edit_data'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch data for edit: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    { 
        try {
            $this->validate($request, [
                'status' => 'required',
            ]);

            $update_data = Size::findOrFail($request->id); // Using findOrFail
            $input = $request->all();
            $update_data->update($input);

            Toastr::success('Data updated successfully', 'Success');
            return redirect()->route('sizes.index');

        } catch (Exception $e) {
            Toastr::error('Failed to update data: '.$e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function inactive(Request $request)
    {
        try {
            $inactive = Size::findOrFail($request->hidden_id);
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
            $active = Size::findOrFail($request->hidden_id);
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
            $delete_data = Size::findOrFail($request->hidden_id);
            $delete_data->delete();

            Toastr::success('Data deleted successfully', 'Success');
            return redirect()->back();

        } catch (Exception $e) {
            Toastr::error('Failed to delete data: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
}

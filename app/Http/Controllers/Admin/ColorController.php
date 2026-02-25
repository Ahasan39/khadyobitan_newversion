<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Toastr;

class ColorController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:color-list|color-create|color-edit|color-delete', ['only' => ['index','show']]);
        $this->middleware('permission:color-create', ['only' => ['create','store']]);
        $this->middleware('permission:color-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:color-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $show_data = Color::orderBy('name','ASC')->get();
            return view('backEnd.color.index',compact('show_data'));
        } catch (\Exception $e) {
            \Log::error('Color index failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load colors');
            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            return view('backEnd.color.create');
        } catch (\Exception $e) {
            \Log::error('Color create page failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load create page');
            return redirect()->route('colors.index');
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'status' => 'required',
            ]);
            
            $input = $request->all();
            
            Color::create($input);        
            
            Toastr::success('Success','Data insert successfully');
            return redirect()->route('colors.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::error('Error', 'Validation failed');
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Color store failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to create color. Please try again.');
            return redirect()->back()->withInput();
        }
    }
    
    public function edit($id)
    {
        try {
            $edit_data = Color::findOrFail($id);
            return view('backEnd.color.edit',compact('edit_data'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Color not found for edit. ID: ' . $id);
            Toastr::error('Error', 'Color not found');
            return redirect()->route('colors.index');
        } catch (\Exception $e) {
            \Log::error('Color edit page failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to load edit page');
            return redirect()->route('colors.index');
        }
    }
    
    public function update(Request $request)
    { 
        try {
            $this->validate($request, [
                'status' => 'required',
            ]);

            $update_data = Color::findOrFail($request->id);
            $input = $request->all();
            $update_data->update($input);

            Toastr::success('Success','Data update successfully');
            return redirect()->route('colors.index');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Color not found for update. ID: ' . $request->id);
            Toastr::error('Error', 'Color not found');
            return redirect()->route('colors.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::error('Error', 'Validation failed');
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Color update failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to update color. Please try again.');
            return redirect()->back()->withInput();
        }
    }
 
    public function inactive(Request $request)
    {
        try {
            $inactive = Color::findOrFail($request->hidden_id);
            $inactive->status = 0;
            $inactive->save();
            Toastr::success('Success','Data inactive successfully');
            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Color not found for inactive. ID: ' . $request->hidden_id);
            Toastr::error('Error', 'Color not found');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Color inactive failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to inactive color');
            return redirect()->back();
        }
    }

    public function active(Request $request)
    {
        try {
            $active = Color::findOrFail($request->hidden_id);
            $active->status = 1;
            $active->save();
            Toastr::success('Success','Data active successfully');
            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Color not found for active. ID: ' . $request->hidden_id);
            Toastr::error('Error', 'Color not found');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Color active failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to active color');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {       
        try {
            $delete_data = Color::findOrFail($request->hidden_id);
            $delete_data->delete();
            Toastr::success('Success','Data delete successfully');
            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Color not found for deletion. ID: ' . $request->hidden_id);
            Toastr::error('Error', 'Color not found');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Color deletion failed: ' . $e->getMessage());
            Toastr::error('Error', 'Failed to delete color. Please try again.');
            return redirect()->back();
        }
    }
}
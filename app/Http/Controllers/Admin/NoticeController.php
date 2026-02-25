<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;


class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notices = Notice::OrderBy('id', 'desc')->get();
        return view('backEnd.notice.index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backEnd.notice.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif,webp',
            'status' => 'required|in:0,1',
        ]);

        // Handle file upload
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/notices'), $filename);
            $data['icon'] = 'uploads/notices/' . $filename;
        }

        Notice::create($data);

        Toastr::success('Success', 'Notice created successfully');
        return redirect()->route('notice.index');
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
        $notices =Notice::findOrFail($id);
        return view('backEnd.notice.edit', compact('notices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif,webp',
            'status' => 'required|in:0,1',
        ]);
        $notices =Notice::findOrFail($id);

        // Handle file upload for update: create folder if needed and remove old image
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/notices');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            // remove old file if exists
            if (!empty($notices->icon) && file_exists(public_path($notices->icon))) {
                @unlink(public_path($notices->icon));
            }

            $file->move($destination, $filename);
            $data['icon'] = 'uploads/notices/' . $filename;
        }

        $notices->update($data);
        Toastr::success('Success', 'Notice updated successfully');
        return redirect()->route('notice.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notices = Notice::findOrFail($id);
        
        try {
            if (!empty($notices->icon) && file_exists(public_path($notices->icon))) {
                unlink(public_path($notices->icon));
            }
            $notices->delete();
            Toastr::success('Success', 'Notice deleted successfully');
            return redirect()->route('notice.index');
        } catch (\Exception $e) {
            Toastr::error('Error', 'Failed to delete notice');
            return redirect()->back();
        }
            
    }

    public function inactive(Request $request)
    {
        $inactive = Notice::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Notice::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }

}
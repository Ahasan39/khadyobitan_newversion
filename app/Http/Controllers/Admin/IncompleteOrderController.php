<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckoutLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;

class IncompleteOrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:order-edit', ['only' => ['updateStatus', 'addNote', 'markAsContacted']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy', 'bulkDelete']]);
    }

    /**
     * Display a listing of incomplete orders
     */
    public function index(Request $request)
    {
        try {
            $query = CheckoutLead::with('contactedBy')->orderBy('created_at', 'desc');

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter by search keyword (name or phone)
            if ($request->has('keyword') && $request->keyword != '') {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->keyword . '%')
                      ->orWhere('phone', 'LIKE', '%' . $request->keyword . '%');
                });
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            $incompleteOrders = $query->paginate(50);

            // Get statistics
            $stats = [
                'total' => CheckoutLead::count(),
                'pending' => CheckoutLead::pending()->count(),
                'contacted' => CheckoutLead::contacted()->count(),
                'converted' => CheckoutLead::converted()->count(),
                'abandoned' => CheckoutLead::abandoned()->count(),
            ];

            return view('backEnd.incomplete_orders.index', compact('incompleteOrders', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Incomplete Orders Index Error: ' . $e->getMessage());
            Toastr::error('Something went wrong while loading incomplete orders', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified incomplete order
     */
    public function show($id)
    {
        try {
            $incompleteOrder = CheckoutLead::with('contactedBy')->findOrFail($id);
            return view('backEnd.incomplete_orders.show', compact('incompleteOrder'));
        } catch (\Exception $e) {
            \Log::error('Incomplete Order Show Error: ' . $e->getMessage());
            Toastr::error('Incomplete order not found', 'Error');
            return redirect()->route('admin.incomplete_orders.index');
        }
    }

    /**
     * Update the status of incomplete order
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,contacted,converted,abandoned'
            ]);

            $incompleteOrder = CheckoutLead::findOrFail($id);
            $incompleteOrder->status = $request->status;
            
            // If marking as contacted, update contacted_at and contacted_by
            if ($request->status == 'contacted' && $incompleteOrder->contacted_at == null) {
                $incompleteOrder->contacted_at = Carbon::now();
                $incompleteOrder->contacted_by = Auth::id();
            }
            
            $incompleteOrder->save();

            Toastr::success('Status updated successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Update Status Error: ' . $e->getMessage());
            Toastr::error('Failed to update status', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Add admin note to incomplete order
     */
    public function addNote(Request $request, $id)
    {
        try {
            $request->validate([
                'admin_note' => 'required|string'
            ]);

            $incompleteOrder = CheckoutLead::findOrFail($id);
            $incompleteOrder->admin_note = $request->admin_note;
            $incompleteOrder->save();

            Toastr::success('Note added successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Add Note Error: ' . $e->getMessage());
            Toastr::error('Failed to add note', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Mark incomplete order as contacted
     */
    public function markAsContacted($id)
    {
        try {
            $incompleteOrder = CheckoutLead::findOrFail($id);
            
            if ($incompleteOrder->status == 'pending') {
                $incompleteOrder->status = 'contacted';
            }
            
            $incompleteOrder->contacted_at = Carbon::now();
            $incompleteOrder->contacted_by = Auth::id();
            $incompleteOrder->save();

            Toastr::success('Marked as contacted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Mark As Contacted Error: ' . $e->getMessage());
            Toastr::error('Failed to mark as contacted', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified incomplete order
     */
    public function destroy($id)
    {
        try {
            $incompleteOrder = CheckoutLead::findOrFail($id);
            $incompleteOrder->delete();

            Toastr::success('Incomplete order deleted successfully', 'Success');
            return redirect()->route('admin.incomplete_orders.index');
        } catch (\Exception $e) {
            \Log::error('Delete Incomplete Order Error: ' . $e->getMessage());
            Toastr::error('Failed to delete incomplete order', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Bulk delete incomplete orders
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('order_ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No orders selected'
                ]);
            }

            CheckoutLead::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => count($ids) . ' incomplete order(s) deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Bulk Delete Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete incomplete orders'
            ]);
        }
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'order_ids' => 'required|array',
                'status' => 'required|in:pending,contacted,converted,abandoned'
            ]);

            $ids = $request->input('order_ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No orders selected'
                ]);
            }

            $updateData = ['status' => $request->status];
            
            // If marking as contacted, update contacted_at and contacted_by
            if ($request->status == 'contacted') {
                $updateData['contacted_at'] = Carbon::now();
                $updateData['contacted_by'] = Auth::id();
            }

            CheckoutLead::whereIn('id', $ids)->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => count($ids) . ' incomplete order(s) status updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Bulk Update Status Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update status'
            ]);
        }
    }
}

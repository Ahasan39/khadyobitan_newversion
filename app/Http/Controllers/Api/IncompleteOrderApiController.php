<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CheckoutLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class IncompleteOrderApiController extends Controller
{
    /**
     * Get all incomplete orders with filters
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = CheckoutLead::with('contactedBy:id,name')->orderBy('created_at', 'desc');

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

            // Pagination
            $perPage = $request->get('per_page', 15);
            $incompleteOrders = $query->paginate($perPage);

            // Get statistics
            $stats = [
                'total' => CheckoutLead::count(),
                'pending' => CheckoutLead::pending()->count(),
                'contacted' => CheckoutLead::contacted()->count(),
                'converted' => CheckoutLead::converted()->count(),
                'abandoned' => CheckoutLead::abandoned()->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Incomplete orders retrieved successfully',
                'data' => [
                    'orders' => $incompleteOrders->items(),
                    'pagination' => [
                        'current_page' => $incompleteOrders->currentPage(),
                        'last_page' => $incompleteOrders->lastPage(),
                        'per_page' => $incompleteOrders->perPage(),
                        'total' => $incompleteOrders->total(),
                        'from' => $incompleteOrders->firstItem(),
                        'to' => $incompleteOrders->lastItem(),
                    ],
                    'statistics' => $stats
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('API Incomplete Orders Index Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve incomplete orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single incomplete order details
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $incompleteOrder = CheckoutLead::with('contactedBy:id,name,email')->find($id);

            if (!$incompleteOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incomplete order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Incomplete order retrieved successfully',
                'data' => [
                    'id' => $incompleteOrder->id,
                    'name' => $incompleteOrder->name,
                    'phone' => $incompleteOrder->phone,
                    'address' => $incompleteOrder->address,
                    'ip' => $incompleteOrder->ip,
                    'status' => $incompleteOrder->status,
                    'status_badge' => $incompleteOrder->status_badge,
                    'admin_note' => $incompleteOrder->admin_note,
                    'cart_data' => $incompleteOrder->cart_data,
                    'total_amount' => $incompleteOrder->total_amount,
                    'contacted_at' => $incompleteOrder->contacted_at,
                    'contacted_by' => $incompleteOrder->contactedBy,
                    'created_at' => $incompleteOrder->created_at,
                    'updated_at' => $incompleteOrder->updated_at,
                    'formatted_created_at' => $incompleteOrder->formatted_created_at,
                    'formatted_contacted_at' => $incompleteOrder->formatted_contacted_at,
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('API Incomplete Order Show Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve incomplete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create incomplete order (from frontend checkout)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string',
                'cart_data' => 'nullable|array',
                'total_amount' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if lead already exists for this phone
            $existingLead = CheckoutLead::where('phone', $request->phone)
                ->where('status', 'pending')
                ->first();

            if ($existingLead) {
                // Update existing lead
                $existingLead->update([
                    'name' => $request->name,
                    'address' => $request->address,
                    'cart_data' => $request->cart_data,
                    'total_amount' => $request->total_amount,
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Incomplete order updated successfully',
                    'data' => $existingLead
                ], 200);
            } else {
                // Create new lead
                $lead = CheckoutLead::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'cart_data' => $request->cart_data,
                    'total_amount' => $request->total_amount,
                    'ip' => $request->ip(),
                    'status' => 'pending',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Incomplete order created successfully',
                    'data' => $lead
                ], 201);
            }

        } catch (\Exception $e) {
            \Log::error('API Incomplete Order Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create incomplete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update incomplete order status
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,contacted,converted,abandoned'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $incompleteOrder = CheckoutLead::find($id);

            if (!$incompleteOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incomplete order not found'
                ], 404);
            }

            $incompleteOrder->status = $request->status;
            
            // If marking as contacted, update contacted_at and contacted_by
            if ($request->status == 'contacted' && $incompleteOrder->contacted_at == null) {
                $incompleteOrder->contacted_at = Carbon::now();
                $incompleteOrder->contacted_by = Auth::id() ?? null;
            }
            
            $incompleteOrder->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $incompleteOrder
            ], 200);

        } catch (\Exception $e) {
            \Log::error('API Update Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add or update admin note
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNote(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'admin_note' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $incompleteOrder = CheckoutLead::find($id);

            if (!$incompleteOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incomplete order not found'
                ], 404);
            }

            $incompleteOrder->admin_note = $request->admin_note;
            $incompleteOrder->save();

            return response()->json([
                'success' => true,
                'message' => 'Note added successfully',
                'data' => $incompleteOrder
            ], 200);

        } catch (\Exception $e) {
            \Log::error('API Add Note Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add note',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark incomplete order as contacted
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsContacted($id)
    {
        try {
            $incompleteOrder = CheckoutLead::find($id);

            if (!$incompleteOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incomplete order not found'
                ], 404);
            }
            
            if ($incompleteOrder->status == 'pending') {
                $incompleteOrder->status = 'contacted';
            }
            
            $incompleteOrder->contacted_at = Carbon::now();
            $incompleteOrder->contacted_by = Auth::id() ?? null;
            $incompleteOrder->save();

            return response()->json([
                'success' => true,
                'message' => 'Marked as contacted successfully',
                'data' => $incompleteOrder
            ], 200);

        } catch (\Exception $e) {
            \Log::error('API Mark As Contacted Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as contacted',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete incomplete order
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $incompleteOrder = CheckoutLead::find($id);

            if (!$incompleteOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incomplete order not found'
                ], 404);
            }

            $incompleteOrder->delete();

            return response()->json([
                'success' => true,
                'message' => 'Incomplete order deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('API Delete Incomplete Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete incomplete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete incomplete orders
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_ids' => 'required|array',
                'order_ids.*' => 'integer|exists:checkout_leads,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $deletedCount = CheckoutLead::whereIn('id', $request->order_ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} incomplete order(s) deleted successfully",
                'data' => [
                    'deleted_count' => $deletedCount
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('API Bulk Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete incomplete orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update status
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_ids' => 'required|array',
                'order_ids.*' => 'integer|exists:checkout_leads,id',
                'status' => 'required|in:pending,contacted,converted,abandoned'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = ['status' => $request->status];
            
            // If marking as contacted, update contacted_at and contacted_by
            if ($request->status == 'contacted') {
                $updateData['contacted_at'] = Carbon::now();
                $updateData['contacted_by'] = Auth::id() ?? null;
            }

            $updatedCount = CheckoutLead::whereIn('id', $request->order_ids)->update($updateData);

            return response()->json([
                'success' => true,
                'message' => "{$updatedCount} incomplete order(s) status updated successfully",
                'data' => [
                    'updated_count' => $updatedCount,
                    'new_status' => $request->status
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('API Bulk Update Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics only
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        try {
            $stats = [
                'total' => CheckoutLead::count(),
                'pending' => CheckoutLead::pending()->count(),
                'contacted' => CheckoutLead::contacted()->count(),
                'converted' => CheckoutLead::converted()->count(),
                'abandoned' => CheckoutLead::abandoned()->count(),
                'today' => CheckoutLead::whereDate('created_at', Carbon::today())->count(),
                'this_week' => CheckoutLead::whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])->count(),
                'this_month' => CheckoutLead::whereMonth('created_at', Carbon::now()->month)->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data' => $stats
            ], 200);

        } catch (\Exception $e) {
            \Log::error('API Statistics Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

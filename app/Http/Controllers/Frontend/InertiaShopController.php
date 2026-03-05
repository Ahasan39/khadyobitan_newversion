<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class InertiaShopController extends Controller
{
    /**
     * Display all products with filtering and sorting
     */
    public function index(Request $request)
    {
        $query = Product::where('status', 1)
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->withCount('variable');

        // Apply sorting
        $query = $this->applySorting($query, $request->sort);

        // Apply category filter if provided
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Apply price range filter if provided
        if ($request->min_price && $request->max_price) {
            $query->whereBetween('new_price', [$request->min_price, $request->max_price]);
        }

        $products = $query->paginate(12);
        $categories = Category::where('status', 1)->select('id', 'name', 'slug')->get();

        return Inertia::render('Shop', [
            'products' => $products,
            'categories' => $categories,
            'currentPath' => '/shop',
            'filters' => [
                'sort' => $request->sort,
                'category' => $request->category,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
            ]
        ]);
    }

    /**
     * Apply sorting to products query
     */
    private function applySorting($query, $sort = null)
    {
        switch ($sort) {
            case 1:
                return $query->orderBy('created_at', 'desc');
            case 2:
                return $query->orderBy('created_at', 'asc');
            case 3:
                return $query->orderBy('new_price', 'desc');
            case 4:
                return $query->orderBy('new_price', 'asc');
            case 5:
                return $query->orderBy('name', 'asc');
            case 6:
                return $query->orderBy('name', 'desc');
            default:
                return $query->latest();
        }
    }

    /**
     * Load more products via AJAX
     */
    public function loadMore(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $perPage = 12;

        $query = Product::where('status', 1)
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'type', 'category_id')
            ->withCount('variable');

        // Apply sorting
        $query = $this->applySorting($query, $request->sort);

        // Apply filters
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->min_price && $request->max_price) {
            $query->whereBetween('new_price', [$request->min_price, $request->max_price]);
        }

        $products = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'products' => $products->items(),
            'has_more_pages' => $products->hasMorePages(),
            'current_page' => $products->currentPage(),
            'total_pages' => $products->lastPage(),
            'total_products' => $products->total(),
        ]);
    }
}

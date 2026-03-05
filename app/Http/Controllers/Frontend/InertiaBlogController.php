<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\CreatePage;

class InertiaBlogController extends Controller
{
    /**
     * Display blog listing page
     */
    public function index(Request $request)
    {
        try {
            $query = CreatePage::where('status', 1)
                ->where('type', 'blog');

            // Search functionality - use where closure for proper grouping
            if ($request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', '%' . $search . '%')
                      ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
            }

            $posts = $query->orderBy('created_at', 'desc')
                ->paginate(12);

            return Inertia::render('Blog', [
                'posts' => $posts,
                'currentPath' => '/blog',
                'search' => $request->search,
            ]);
        } catch (\Exception $e) {
            // If database table doesn't exist or other error, render with empty posts
            return Inertia::render('Blog', [
                'posts' => [],
                'currentPath' => '/blog',
                'search' => $request->search,
            ]);
        }
    }

    /**
     * Display single blog post
     */
    public function show($slug)
    {
        try {
            $post = CreatePage::where('slug', $slug)
                ->where('status', 1)
                ->where('type', 'blog')
                ->firstOrFail();

            // Get related posts
            $relatedPosts = CreatePage::where('status', 1)
                ->where('type', 'blog')
                ->where('id', '!=', $post->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return Inertia::render('BlogDetail', [
                'post' => $post,
                'relatedPosts' => $relatedPosts,
                'currentPath' => "/blog/{$slug}",
            ]);
        } catch (\Exception $e) {
            // If post not found or table doesn't exist, return 404
            abort(404);
        }
    }
}

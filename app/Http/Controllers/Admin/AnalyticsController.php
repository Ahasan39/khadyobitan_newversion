<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

     
        $todayVisitors = Visit::whereDate('created_at', $today)->count();

        
        $yesterdayVisitors = Visit::whereDate('created_at', $yesterday)->count();

       
        $thisWeekVisitors = Visit::where('created_at', '>=', $startOfWeek)->count();

    
        $thisMonthVisitors = Visit::where('created_at', '>=', $startOfMonth)->count();

        
        $lastWeekVisitors = Visit::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count();

        $lastMonthVisitors = Visit::whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ])->count();

        
        $totalVisitors = Visit::count();

      
        $activeVisitors = Visit::where('created_at', '>=', Carbon::now()->subMinutes(5))->count();

      
        $dates = Visit::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('date');

        $visitors = Visit::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count');

        return view('backEnd.analytics.dashboard', compact(
            'dates',
            'visitors',
            'activeVisitors',
            'todayVisitors',
            'yesterdayVisitors',
            'lastWeekVisitors',
            'thisWeekVisitors',
            'lastMonthVisitors',
            'thisMonthVisitors',
            'totalVisitors'
        ));
    }
}

<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test InertiaHomeController queries
try {
    $cats = App\Models\Category::where(['front_view'=>1,'status'=>1])
        ->select('id','name','slug','image','front_view','status')
        ->orderBy('id','ASC')->get()
        ->map(function($cat) {
            $cat->count = App\Models\Product::where('status',1)->where('category_id',$cat->id)->count();
            return $cat;
        });
    echo 'Home categories: ' . $cats->count() . PHP_EOL;
} catch(Exception $e) { echo 'Home cats error: ' . $e->getMessage() . PHP_EOL; }

// Test withCount('variable')
try {
    $prods = App\Models\Product::where(['status'=>1,'topsale'=>1])
        ->with(['image','category'])
        ->select('id','name','slug','new_price','old_price','type','category_id')
        ->orderBy('id','DESC')
        ->withCount('variable')
        ->limit(3)->get();
    echo 'Hot deal prods: ' . $prods->count() . PHP_EOL;
    echo 'Sample: ' . json_encode($prods->first()) . PHP_EOL;
} catch(Exception $e) { echo 'Hot deal error: ' . $e->getMessage() . PHP_EOL; }

// Test FrontendController category
try {
    $data = App\Models\Category::where('status',1)
        ->select('id','slug','name','image')
        ->withCount('productsCount')
        ->with('tags')
        ->get();
    echo 'API categories: ' . $data->count() . PHP_EOL;
} catch(Exception $e) { echo 'API cats error: ' . $e->getMessage() . PHP_EOL; }

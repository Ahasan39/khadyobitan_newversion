<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ResponseCacheService;
use App\Http\Controllers\Api\OptimizedFrontendController;
use Illuminate\Support\Facades\Cache;

class CacheManagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:manage {action : The action to perform (clear|warm|stats|clear-products)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application cache (clear, warm up, view stats, clear products)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'clear':
                return $this->clearCache();
            
            case 'warm':
                return $this->warmCache();
            
            case 'stats':
                return $this->showStats();
            
            case 'clear-products':
                return $this->clearProductCache();
            
            default:
                $this->error("Invalid action: {$action}");
                $this->info('Available actions: clear, warm, stats, clear-products');
                return 1;
        }
    }

    /**
     * Clear all application cache
     */
    private function clearCache()
    {
        $this->info('Clearing all application cache...');
        
        try {
            // Clear application cache
            $this->call('cache:clear');
            
            // Clear config cache
            $this->call('config:clear');
            
            // Clear route cache
            $this->call('route:clear');
            
            // Clear view cache
            $this->call('view:clear');
            
            // Clear compiled classes
            $this->call('clear-compiled');
            
            $this->info('✓ All caches cleared successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to clear cache: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Warm up application cache
     */
    private function warmCache()
    {
        $this->info('Warming up application cache...');
        
        try {
            // Cache configuration
            $this->info('Caching configuration...');
            $this->call('config:cache');
            
            // Cache routes
            $this->info('Caching routes...');
            $this->call('route:cache');
            
            // Cache views
            $this->info('Caching views...');
            $this->call('view:cache');
            
            // Warm up custom caches
            $this->info('Warming up custom caches...');
            $result = ResponseCacheService::warmUp();
            
            if ($result['success']) {
                $this->info("✓ {$result['message']}");
                $this->info("✓ Cached {$result['cached_items']} items");
            } else {
                $this->warn("⚠ {$result['message']}: {$result['error']}");
            }
            
            $this->info('✓ Cache warm up completed!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to warm up cache: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Show cache statistics
     */
    private function showStats()
    {
        $this->info('Cache Statistics:');
        $this->newLine();
        
        try {
            // Get cache driver
            $driver = config('cache.default');
            $this->info("Cache Driver: {$driver}");
            $this->newLine();
            
            // Get cache stats
            $stats = ResponseCacheService::getStats();
            
            if (isset($stats['error'])) {
                $this->warn("Unable to retrieve stats: {$stats['error']}");
            } elseif (isset($stats['message'])) {
                $this->info($stats['message']);
            } else {
                $this->table(
                    ['Metric', 'Value'],
                    collect($stats)->map(function ($value, $key) {
                        return [$key, is_array($value) ? json_encode($value) : $value];
                    })->toArray()
                );
            }
            
            // Check if specific caches exist
            $this->newLine();
            $this->info('Checking common caches:');
            
            $caches = [
                'categories.all' => 'Categories',
                'brands.all' => 'Brands',
                'settings.general' => 'General Settings',
                'api.products.all' => 'All Products',
                'api.products.featured' => 'Featured Products',
            ];
            
            foreach ($caches as $key => $label) {
                $exists = Cache::has($key);
                $status = $exists ? '✓ Cached' : '✗ Not Cached';
                $this->line("{$label}: {$status}");
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to retrieve cache stats: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clear product-related caches
     */
    private function clearProductCache()
    {
        $this->info('Clearing product caches...');
        
        try {
            OptimizedFrontendController::clearProductCache();
            
            $this->info('✓ Product caches cleared successfully!');
            $this->info('The following caches were cleared:');
            $this->line('  - All products');
            $this->line('  - Featured products');
            $this->line('  - Top rated products');
            $this->line('  - Category products');
            $this->line('  - Subcategory products');
            $this->line('  - Child category products');
            $this->line('  - Brand products');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to clear product cache: ' . $e->getMessage());
            return 1;
        }
    }
}

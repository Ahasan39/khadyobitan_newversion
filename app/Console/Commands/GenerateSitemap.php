<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\CreatePage;
class GenerateSitemap extends Command
{
    protected $signature = 'app:generate-sitemap';
    protected $description = 'Generate sitemap.xml for the website';
    public function handle()
    {
        $now = Carbon::now();

        $sitemap = Sitemap::create()
            ->add(Url::create('/')
                ->setLastModificationDate($now)
                ->setPriority(1.0)
            )
            ->add(Url::create('/site/contact-us')
                ->setLastModificationDate($now)
                ->setPriority(0.8)
            );

        foreach (Category::where('status', 1)->get() as $category) {
            $sitemap->add(
                Url::create("/category/{$category->slug}")
                    ->setLastModificationDate($category->updated_at ?? $now)
                    ->setPriority(0.8)
            );
        }

        foreach (Subcategory::where('status', 1)->get() as $subcategory) {
            $sitemap->add(
                Url::create("/subcategory/{$subcategory->slug}")
                    ->setLastModificationDate($subcategory->updated_at ?? $now)
                    ->setPriority(0.8)
            );
        }

        foreach (Brand::where('status', 1)->get() as $brand) {
            $sitemap->add(
                Url::create("/brand/{$brand->slug}")
                    ->setLastModificationDate($brand->updated_at ?? $now)
                    ->setPriority(0.8)
            );
        }

        foreach (Campaign::where('status', 1)->get() as $campaign) {
            $sitemap->add(
                Url::create("/campaign/{$campaign->slug}")
                    ->setLastModificationDate($campaign->updated_at ?? $now)
                    ->setPriority(0.8)
            );
        }

        foreach (CreatePage::where('status', 1)->get() as $page) {
            $sitemap->add(
                Url::create("/page/{$page->slug}")
                    ->setLastModificationDate($page->updated_at ?? $now)
                    ->setPriority(0.8)
            );
        }


        foreach (Product::where('status', 1)->get() as $product) {
            $sitemap->add(
                Url::create("/product/{$product->slug}")
                    ->setLastModificationDate($product->updated_at ?? $now)
                    ->setPriority(0.8)
            );
        }


        $sitemap->writeToFile('sitemap.xml');

        $this->info('✅ sitemap.xml created successfully!');
    }
}

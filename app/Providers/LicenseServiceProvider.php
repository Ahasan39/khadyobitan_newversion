<?php
namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class LicenseServiceProvider extends ServiceProvider
{
public function boot(Request $request)
 {
    // Ensure local environment skips license check
    


  }
}

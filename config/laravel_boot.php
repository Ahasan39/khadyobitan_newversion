<?php

use Illuminate\Support\Facades\Http;

$files = [
    base_path('app/Http/Middleware/LaravelSecurity.php'),
    base_path('app/Providers/LicenseServiceProvider.php'),
];

$totalLines = 0;
foreach ($files as $file) {
    if (!file_exists($file)) {
        // \Artisan::call('down');
        die("Warning!! You tried to cheat.");
    }
    $lines = file($file);
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (
            $trimmed === '' ||
            str_starts_with($trimmed, '/*') ||
            str_starts_with($trimmed, '*') ||
            str_starts_with($trimmed, '*/')
        ) {
            continue;
        }
        $totalLines++;
    }
}
// die("Total code lines: $totalLines");
// if ($totalLines != 50) {
//     // \Artisan::call('down');
//     die("Warning!! You tried to cheat.");
// }


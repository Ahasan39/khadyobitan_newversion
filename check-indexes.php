<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n";
echo "================================================================================\n";
echo "                    CHECKING DATABASE INDEXES\n";
echo "================================================================================\n\n";

$tables = [
    'products',
    'orders',
    'categories',
    'subcategories',
    'childcategories',
    'brands',
    'customers',
    'campaigns',
    'reviews'
];

$totalIndexes = 0;

foreach ($tables as $table) {
    try {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name LIKE '%_idx' OR Key_name LIKE '%_index'");
        
        if (count($indexes) > 0) {
            echo "Table: {$table}\n";
            echo str_repeat("-", 80) . "\n";
            
            $uniqueIndexes = [];
            foreach ($indexes as $index) {
                if (!in_array($index->Key_name, $uniqueIndexes)) {
                    $uniqueIndexes[] = $index->Key_name;
                    echo "  ✓ {$index->Key_name} on column: {$index->Column_name}\n";
                    $totalIndexes++;
                }
            }
            echo "\n";
        } else {
            echo "Table: {$table}\n";
            echo str_repeat("-", 80) . "\n";
            echo "  ✗ No performance indexes found\n\n";
        }
    } catch (\Exception $e) {
        echo "Table: {$table}\n";
        echo str_repeat("-", 80) . "\n";
        echo "  ✗ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "================================================================================\n";
echo "                    SUMMARY\n";
echo "================================================================================\n\n";
echo "Total Performance Indexes Found: {$totalIndexes}\n";
echo "Expected: 29 indexes\n\n";

if ($totalIndexes >= 29) {
    echo "✓ SUCCESS: All performance indexes are in place!\n";
} elseif ($totalIndexes > 0) {
    echo "⚠ WARNING: Some indexes are missing. Expected 29, found {$totalIndexes}\n";
} else {
    echo "✗ ERROR: No performance indexes found. Migration may have failed.\n";
}

echo "\n";
echo "================================================================================\n\n";

<?php

// Test script to debug feature toggle creation
// Run this with: php test-feature-creation.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Feature Toggle Creation Debug ===\n\n";

// Test 1: Check if table exists
echo "1. Checking if feature_toggles table exists...\n";
try {
    $tableExists = Schema::hasTable('feature_toggles');
    echo $tableExists ? "   ✓ Table exists\n" : "   ✗ Table does NOT exist\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 2: Check if permission exists
echo "\n2. Checking if 'setting-create' permission exists...\n";
try {
    $permission = \Spatie\Permission\Models\Permission::where('name', 'setting-create')->first();
    echo $permission ? "   ✓ Permission exists (ID: {$permission->id})\n" : "   ✗ Permission does NOT exist\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 3: Check current user permissions
echo "\n3. Checking current admin user permissions...\n";
try {
    $user = \App\Models\User::first();
    if ($user) {
        echo "   User: {$user->name} (ID: {$user->id})\n";
        $hasPermission = $user->hasPermissionTo('setting-create');
        echo $hasPermission ? "   ✓ User HAS 'setting-create' permission\n" : "   ✗ User does NOT have 'setting-create' permission\n";
        
        // Check via role
        $roles = $user->roles->pluck('name')->toArray();
        echo "   User roles: " . implode(', ', $roles) . "\n";
        
        foreach ($user->roles as $role) {
            $roleHasPermission = $role->hasPermissionTo('setting-create');
            echo "   Role '{$role->name}' " . ($roleHasPermission ? "HAS" : "does NOT have") . " permission\n";
        }
    } else {
        echo "   ✗ No users found\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 4: Try to create a test feature
echo "\n4. Attempting to create a test feature...\n";
try {
    $testFeature = \App\Models\FeatureToggle::create([
        'feature_key' => 'test_debug_' . time(),
        'feature_name' => 'Test Debug Feature',
        'is_enabled' => false,
        'settings' => ['test' => 'value']
    ]);
    echo "   ✓ Feature created successfully! (ID: {$testFeature->id})\n";
    echo "   Deleting test feature...\n";
    $testFeature->delete();
    echo "   ✓ Test feature deleted\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 5: Check existing features
echo "\n5. Checking existing features...\n";
try {
    $count = \App\Models\FeatureToggle::count();
    echo "   Total features: {$count}\n";
    if ($count > 0) {
        $features = \App\Models\FeatureToggle::select('id', 'feature_key', 'feature_name', 'is_enabled')->get();
        foreach ($features as $feature) {
            echo "   - {$feature->feature_name} ({$feature->feature_key}) - " . ($feature->is_enabled ? 'Enabled' : 'Disabled') . "\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Debug Complete ===\n";
echo "\nIf all tests pass, the issue is likely:\n";
echo "1. JavaScript error preventing form submission\n";
echo "2. CSRF token mismatch\n";
echo "3. Form validation failing silently\n";
echo "\nCheck browser console (F12) for JavaScript errors.\n";

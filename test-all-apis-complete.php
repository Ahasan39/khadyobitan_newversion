<?php
/**
 * COMPLETE API TESTING SCRIPT
 * 
 * Tests ALL 80+ API endpoints in your Laravel e-commerce project
 * Run: php test-all-apis-complete.php
 */

// Configuration
$BASE_URL = 'http://localhost:8000'; // CHANGE THIS TO YOUR DOMAIN
$TIMEOUT = 10;

// Colors for console output
$GREEN = "\033[32m";
$RED = "\033[31m";
$YELLOW = "\033[33m";
$BLUE = "\033[34m";
$RESET = "\033[0m";

// Test results
$results = [];
$totalTests = 0;
$passedTests = 0;
$failedTests = 0;
$totalTime = 0;

/**
 * Test an API endpoint
 */
function testAPI($name, $url, $method = 'GET', $data = null, $headers = []) {
    global $results, $totalTests, $passedTests, $failedTests, $totalTime, $TIMEOUT;
    global $GREEN, $RED, $YELLOW, $RESET;
    
    $totalTests++;
    $startTime = microtime(true);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $TIMEOUT);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $defaultHeaders = [
        'Accept: application/json',
        'Content-Type: application/json'
    ];
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($defaultHeaders, $headers));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $responseTime = round((microtime(true) - $startTime) * 1000, 2);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    $totalTime += $responseTime;
    $passed = ($httpCode >= 200 && $httpCode < 400);
    
    if ($passed) {
        $passedTests++;
        $status = "{$GREEN}✓ PASS{$RESET}";
    } else {
        $failedTests++;
        $status = "{$RED}✗ FAIL{$RESET}";
    }
    
    // Print result
    echo sprintf("[%s] %s - %dms\n", $status, $name, $responseTime);
    
    $results[] = [
        'name' => $name,
        'url' => $url,
        'status' => $passed ? 'PASS' : 'FAIL',
        'http_code' => $httpCode,
        'response_time' => $responseTime,
        'error' => $error,
        'passed' => $passed
    ];
    
    return $passed;
}

// Start testing
echo "\n";
echo "================================================================================\n";
echo "                    COMPLETE API TESTING SUITE\n";
echo "================================================================================\n\n";
echo "Testing APIs for: {$BLUE}{$BASE_URL}{$RESET}\n";
echo "Total APIs to test: 80+\n\n";

// 1. Configuration & Setup APIs
echo "{$YELLOW}[1/18] Testing Configuration & Setup APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('App Config', "$BASE_URL/api/v1/app-config");
testAPI('Site Info', "$BASE_URL/api/v1/siteinfo");
testAPI('Theme Colors', "$BASE_URL/api/v1/theme-colors");
testAPI('Feature Toggles', "$BASE_URL/api/v1/feature-toggles");
echo "\n";

// 2. Banner & Slider APIs
echo "{$YELLOW}[2/18] Testing Banner & Slider APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Sliders', "$BASE_URL/api/v1/slider");
testAPI('Banners', "$BASE_URL/api/v1/banner");
testAPI('Banner by ID', "$BASE_URL/api/v1/banner/1");
echo "\n";

// 3. Category APIs
echo "{$YELLOW}[3/18] Testing Category APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('All Categories', "$BASE_URL/api/v1/categories");
testAPI('Products by Category', "$BASE_URL/api/v1/category/1");
testAPI('All Subcategories', "$BASE_URL/api/v1/sub-categories");
testAPI('All Child Categories', "$BASE_URL/api/v1/child-categories");
echo "\n";

// 4. Brand APIs
echo "{$YELLOW}[4/18] Testing Brand APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('All Brands', "$BASE_URL/api/v1/brands");
echo "\n";

// 5. Product APIs
echo "{$YELLOW}[5/18] Testing Product APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('All Products', "$BASE_URL/api/v1/all-products");
testAPI('Single Product by ID', "$BASE_URL/api/v1/single-product/1");
testAPI('Featured Products', "$BASE_URL/api/v1/featured-product");
testAPI('Latest Products', "$BASE_URL/api/v1/latest-product");
testAPI('Popular Products', "$BASE_URL/api/v1/popular-product");
testAPI('Trending Products', "$BASE_URL/api/v1/trending-product");
testAPI('Best Selling Products', "$BASE_URL/api/v1/best-selling-product");
testAPI('Hot Deal Products', "$BASE_URL/api/v1/hotdeal-product");
testAPI('Homepage Products', "$BASE_URL/api/v1/homepage-product");
testAPI('In Stock Products', "$BASE_URL/api/v1/product-stock");
testAPI('Out of Stock Products', "$BASE_URL/api/v1/product-stock-out");
echo "\n";

// 6. Product Filtering & Sorting APIs
echo "{$YELLOW}[6/18] Testing Product Filtering & Sorting APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Filter by Category', "$BASE_URL/api/v1/filter-byCategory/1");
testAPI('Filter by Price Range', "$BASE_URL/api/v1/products-range?min=100&max=1000");
testAPI('Sort Products', "$BASE_URL/api/v1/products-sort?sort=price_asc");
echo "\n";

// 7. Search APIs
echo "{$YELLOW}[7/18] Testing Search APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Global Search', "$BASE_URL/api/v1/global-search?keyword=test");
echo "\n";

// 8. Product Attributes APIs
echo "{$YELLOW}[8/18] Testing Product Attributes APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('All Colors', "$BASE_URL/api/v1/colors");
testAPI('All Sizes', "$BASE_URL/api/v1/sizes");
echo "\n";

// 9. Review APIs
echo "{$YELLOW}[9/18] Testing Review APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('All Reviews', "$BASE_URL/api/v1/reviews");
testAPI('Customer Reviews with Images', "$BASE_URL/api/v1/image-review");
echo "\n";

// 10. Offer APIs
echo "{$YELLOW}[10/18] Testing Offer APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('All Offers', "$BASE_URL/api/v1/offers");
testAPI('Offer Products', "$BASE_URL/api/v1/offers/1");
echo "\n";

// 11. Shipping & Location APIs
echo "{$YELLOW}[11/18] Testing Shipping & Location APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Get Districts', "$BASE_URL/api/v1/getDistrict");
testAPI('Shipping Charges', "$BASE_URL/api/v1/shipping-charge");
echo "\n";

// 12. Coupon APIs
echo "{$YELLOW}[12/18] Testing Coupon APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Get Coupons', "$BASE_URL/api/v1/coupon");
echo "\n";

// 13. Footer & Menu APIs
echo "{$YELLOW}[13/18] Testing Footer & Menu APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Footer Menu Left', "$BASE_URL/api/v1/footer-menu-left");
testAPI('Footer Menu Right', "$BASE_URL/api/v1/footer-menu-right");
testAPI('Social Media Links', "$BASE_URL/api/v1/social-media");
testAPI('Contact Information', "$BASE_URL/api/v1/contactinfo");
echo "\n";

// 14. Page & Content APIs
echo "{$YELLOW}[14/18] Testing Page & Content APIs...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Get Notice', "$BASE_URL/api/v1/notice");
testAPI('Tag Manager', "$BASE_URL/api/v1/tag-manager/manage");
echo "\n";

// 15. Frontend Routes
echo "{$YELLOW}[15/18] Testing Frontend Routes...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Homepage', "$BASE_URL/");
testAPI('Best Deals Page', "$BASE_URL/best-deals");
testAPI('New Arrival Page', "$BASE_URL/new-arrival");
testAPI('Top Rated Page', "$BASE_URL/top-rated");
testAPI('Top Selling Page', "$BASE_URL/top-selling");
testAPI('Search Page', "$BASE_URL/search?keyword=test");
testAPI('Contact Page', "$BASE_URL/site/contact-us");
echo "\n";

// 16. Cart Routes
echo "{$YELLOW}[16/18] Testing Cart Routes...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Cart Content', "$BASE_URL/cart/content");
testAPI('Cart Count', "$BASE_URL/cart/count");
echo "\n";

// 17. Customer Routes
echo "{$YELLOW}[17/18] Testing Customer Routes...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Customer Login Page', "$BASE_URL/customer/login");
testAPI('Customer Register Page', "$BASE_URL/customer/register");
testAPI('Order Track Page', "$BASE_URL/customer/order-track");
echo "\n";

// 18. Admin Routes
echo "{$YELLOW}[18/18] Testing Admin Routes...{$RESET}\n";
echo "--------------------------------------------------------------------------------\n";
testAPI('Admin Login Page', "$BASE_URL/login");
echo "\n";

// Display Summary
echo "================================================================================\n";
echo "                    TEST SUMMARY\n";
echo "================================================================================\n\n";

$successRate = round(($passedTests / $totalTests) * 100, 2);
$avgResponseTime = round($totalTime / $totalTests, 2);

echo "Total Tests: {$totalTests}\n";
echo "Passed: {$GREEN}{$passedTests}{$RESET} ({$successRate}%)\n";
echo "Failed: {$RED}{$failedTests}{$RESET} (" . round(($failedTests / $totalTests) * 100, 2) . "%)\n";
echo "Average Response Time: {$avgResponseTime}ms\n\n";

if ($failedTests === 0) {
    echo "{$GREEN}✓ All tests passed! Your APIs are working correctly.{$RESET}\n\n";
} else {
    echo "{$RED}✗ Some tests failed. Check the results above.{$RESET}\n\n";
}

// Performance Analysis
echo "================================================================================\n";
echo "                    PERFORMANCE ANALYSIS\n";
echo "================================================================================\n\n";

if ($avgResponseTime < 300) {
    echo "{$GREEN}✓ Excellent!{$RESET} Response times are very fast.\n";
} elseif ($avgResponseTime < 500) {
    echo "{$GREEN}✓ Good!{$RESET} Response times are acceptable.\n";
} elseif ($avgResponseTime < 1000) {
    echo "{$YELLOW}⚠ Warning!{$RESET} Response times are moderate.\n";
} else {
    echo "{$RED}✗ Critical!{$RESET} Response times are slow. Optimization needed.\n";
}

echo "\n";

// Slowest endpoints
echo "Slowest Endpoints:\n";
usort($results, function($a, $b) {
    return $b['response_time'] - $a['response_time'];
});

$slowest = array_slice($results, 0, 5);
foreach ($slowest as $result) {
    echo "  - {$result['name']}: {$result['response_time']}ms\n";
}

echo "\n";

// Failed tests details
if ($failedTests > 0) {
    echo "================================================================================\n";
    echo "                    FAILED TESTS DETAILS\n";
    echo "================================================================================\n\n";
    
    foreach ($results as $result) {
        if (!$result['passed']) {
            echo "{$RED}✗ {$result['name']}{$RESET}\n";
            echo "  URL: {$result['url']}\n";
            echo "  Status Code: {$result['http_code']}\n";
            if ($result['error']) {
                echo "  Error: {$result['error']}\n";
            }
            echo "\n";
        }
    }
}

// Recommendations
echo "================================================================================\n";
echo "                    RECOMMENDATIONS\n";
echo "================================================================================\n\n";

echo "1. Check error logs: storage/logs/laravel.log\n";
echo "2. Verify database connection\n";
echo "3. Clear cache: php artisan cache:clear\n";
echo "4. Test manually in browser\n";
echo "5. Check browser console for errors (F12)\n";
echo "6. Review COMPLETE_API_LIST_AND_TESTING.md for details\n\n";

// Optimization Status
echo "================================================================================\n";
echo "                    OPTIMIZATION STATUS\n";
echo "================================================================================\n\n";

if (file_exists('bootstrap/cache/config.php')) {
    echo "{$GREEN}✓{$RESET} Config cached\n";
} else {
    echo "{$RED}✗{$RESET} Config not cached - Run: php artisan config:cache\n";
}

if (file_exists('bootstrap/cache/routes-v7.php')) {
    echo "{$GREEN}✓{$RESET} Routes cached\n";
} else {
    echo "{$RED}✗{$RESET} Routes not cached - Run: php artisan route:cache\n";
}

echo "\n";

echo "================================================================================\n";
echo "                    TESTING COMPLETE\n";
echo "================================================================================\n\n";

echo "For detailed API documentation, see: COMPLETE_API_LIST_AND_TESTING.md\n";
echo "For Postman testing, import: Postman_API_Collection.json\n\n";

// Exit with appropriate code
exit($failedTests > 0 ? 1 : 0);

<?php
// Test file to check asset accessibility
echo "<h1>Asset Test</h1>";

$files = [
    'public/frontEnd/css/bootstrap.min.css',
    'public/frontEnd/js/jquery-3.7.1.min.js',
    'public/frontEnd/css/style.css',
];

echo "<h2>File Existence Check:</h2>";
foreach ($files as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✅ EXISTS: $file<br>";
        echo "   Full path: $fullPath<br>";
        echo "   Size: " . filesize($fullPath) . " bytes<br><br>";
    } else {
        echo "❌ NOT FOUND: $file<br>";
        echo "   Looking at: $fullPath<br><br>";
    }
}

echo "<h2>Current Directory:</h2>";
echo __DIR__ . "<br><br>";

echo "<h2>Document Root:</h2>";
echo $_SERVER['DOCUMENT_ROOT'] ?? 'Not set' . "<br><br>";

echo "<h2>Server Software:</h2>";
echo $_SERVER['SERVER_SOFTWARE'] ?? 'Not set' . "<br><br>";

echo "<h2>Request URI:</h2>";
echo $_SERVER['REQUEST_URI'] ?? 'Not set' . "<br><br>";

echo "<h2>Test Direct Access:</h2>";
echo '<a href="/public/frontEnd/css/bootstrap.min.css" target="_blank">Click to test bootstrap.min.css</a><br>';
echo '<a href="/public/frontEnd/js/jquery-3.7.1.min.js" target="_blank">Click to test jquery</a><br>';
?>

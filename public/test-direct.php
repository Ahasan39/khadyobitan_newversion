<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Asset Test</title>
</head>
<body>
    <h1>Direct Asset Test</h1>
    
    <h2>Testing CSS Load:</h2>
    <link rel="stylesheet" href="/public/frontEnd/css/bootstrap.min.css">
    <p>If this text is styled with Bootstrap, CSS is loading!</p>
    <button class="btn btn-primary">Bootstrap Button</button>
    
    <h2>Testing JS Load:</h2>
    <script src="/public/frontEnd/js/jquery-3.7.1.min.js"></script>
    <script>
        if (typeof jQuery !== 'undefined') {
            document.write('<p style="color: green;">✅ jQuery loaded successfully!</p>');
        } else {
            document.write('<p style="color: red;">❌ jQuery NOT loaded!</p>');
        }
    </script>
    
    <h2>File Paths:</h2>
    <?php
    echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
    echo "<p>Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";
    echo "<p>Current Dir: " . __DIR__ . "</p>";
    
    $cssFile = __DIR__ . '/frontEnd/css/bootstrap.min.css';
    $jsFile = __DIR__ . '/frontEnd/js/jquery-3.7.1.min.js';
    
    echo "<p>CSS File exists: " . (file_exists($cssFile) ? '✅ YES' : '❌ NO') . "</p>";
    echo "<p>CSS Path: $cssFile</p>";
    
    echo "<p>JS File exists: " . (file_exists($jsFile) ? '✅ YES' : '❌ NO') . "</p>";
    echo "<p>JS Path: $jsFile</p>";
    ?>
    
    <h2>Direct Links:</h2>
    <p><a href="/public/frontEnd/css/bootstrap.min.css" target="_blank">Test Bootstrap CSS</a></p>
    <p><a href="/public/frontEnd/js/jquery-3.7.1.min.js" target="_blank">Test jQuery JS</a></p>
    <p><a href="/frontEnd/css/bootstrap.min.css" target="_blank">Test Bootstrap CSS (without /public)</a></p>
    <p><a href="/frontEnd/js/jquery-3.7.1.min.js" target="_blank">Test jQuery JS (without /public)</a></p>
</body>
</html>

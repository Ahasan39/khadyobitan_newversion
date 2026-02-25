<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Basic Meta Tags -->
    <title>{{ $metaTags['title'] }}</title>
    <meta name="description" content="{{ $metaTags['description'] }}">
    
    <!-- Open Graph Meta Tags (Facebook, LinkedIn) -->
    <meta property="og:title" content="{{ $metaTags['title'] }}">
    <meta property="og:description" content="{{ $metaTags['description'] }}">
    <meta property="og:image" content="{{ $metaTags['image'] }}">
    <meta property="og:url" content="{{ $metaTags['url'] }}">
    <meta property="og:type" content="product">
    <meta property="og:site_name" content="Daamkam">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTags['title'] }}">
    <meta name="twitter:description" content="{{ $metaTags['description'] }}">
    <meta name="twitter:image" content="{{ $metaTags['image'] }}">
    
    <!-- WhatsApp Meta Tags -->
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    
    <!-- Redirect to Vue.js App -->
    <script>
        window.location.href = "{{ config('app.frontend_url') }}/product/{{ $product->slug ?? '' }}";
    </script>
    
    <!-- Fallback for JavaScript disabled -->
    <noscript>
        <meta http-equiv="refresh" content="0; url={{ config('app.frontend_url') }}/product/{{ $product->slug ?? '' }}">
    </noscript>
</head>
<body>
    <h1>{{ $metaTags['title'] }}</h1>
    <img src="{{ $metaTags['image'] }}" alt="{{ $metaTags['title'] }}">
    <p>Redirecting...</p>
</body>
</html>
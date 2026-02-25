$content = Get-Content 'resources\js\Pages\Index.tsx' -Raw
$content = $content -replace 'to="/shop"', 'href="/shop"'
$content = $content -replace 'to="/blog"', 'href="/blog"'
$content = $content -replace 'to=\{`/blog/\$\{post\.slug\}`\}', 'href={`/blog/${post.slug}`}'
$content = $content -replace 'to=\{`/shop\?category=\$\{cat\.slug\}`\}', 'href={`/shop?category=${cat.slug}`}'
$content = $content -replace '  return \(\s+<div>', '  return (`n    <>`n      <Head title="Home - Khadyobitan" />`n      <div>'
$content = $content -replace '    </div>\s+\);\s+\};', '      </div>`n    </>`n  );`n};'
Set-Content 'resources\js\Pages\Index.tsx' -Value $content
Write-Host "Index.tsx updated successfully!"

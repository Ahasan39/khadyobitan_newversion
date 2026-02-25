# Batch update all remaining pages for Inertia.js
Write-Host "========================================"
Write-Host "Updating All Remaining Pages for Inertia.js"
Write-Host "========================================`n"

$pages = @(
    @{Name="Login.tsx"; Title="Login"},
    @{Name="Account.tsx"; Title="My Account"},
    @{Name="Wishlist.tsx"; Title="Wishlist"},
    @{Name="OrderTracking.tsx"; Title="Track Order"},
    @{Name="OrderConfirmation.tsx"; Title="Order Confirmation"},
    @{Name="About.tsx"; Title="About Us"},
    @{Name="Contact.tsx"; Title="Contact Us"},
    @{Name="FAQ.tsx"; Title="FAQ"},
    @{Name="Blog.tsx"; Title="Blog"},
    @{Name="BlogDetail.tsx"; Title="Blog"},
    @{Name="Privacy.tsx"; Title="Privacy Policy"},
    @{Name="Terms.tsx"; Title="Terms & Conditions"},
    @{Name="ReturnPolicy.tsx"; Title="Return Policy"},
    @{Name="ShippingPolicy.tsx"; Title="Shipping Policy"},
    @{Name="NotFound.tsx"; Title="404 - Page Not Found"}
)

$updated = 0

foreach ($page in $pages) {
    $filePath = "resources\js\Pages\$($page.Name)"
    
    if (Test-Path $filePath) {
        Write-Host "Updating $($page.Name)..."
        
        $content = Get-Content $filePath -Raw
        
        # Update imports
        $content = $content -replace 'import \{ Link, useNavigate \} from "react-router-dom";', 'import { Head, Link, router } from "@inertiajs/react";'
        $content = $content -replace 'import \{ useNavigate, Link \} from "react-router-dom";', 'import { Head, Link, router } from "@inertiajs/react";'
        $content = $content -replace 'import \{ Link \} from "react-router-dom";', 'import { Head, Link } from "@inertiajs/react";'
        $content = $content -replace 'import \{ useNavigate \} from "react-router-dom";', 'import { Head, router } from "@inertiajs/react";'
        
        # Update Link to= to href=
        $content = $content -replace 'to="/', 'href="/'
        $content = $content -replace 'to=\{', 'href={'
        
        # Replace useNavigate
        $content = $content -replace 'const navigate = useNavigate\(\);', ''
        $content = $content -replace 'navigate\(', 'router.visit('
        
        # Add Head component
        if ($content -notmatch '<Head') {
            $title = $page.Title
            $content = $content -replace '  return \(\s+<div', "  return (`n    <>`n      <Head title=`"$title - Khadyobitan`" />`n      <div"
            $content = $content -replace '</div>\s+\);\s+\};\s+export default', "</div>`n    </>`n  );`n};`nexport default"
        }
        
        Set-Content $filePath -Value $content
        Write-Host "  Updated $($page.Name) successfully!"
        $updated++
    }
}

Write-Host "`n========================================"
Write-Host "Update Complete!"
Write-Host "========================================"
Write-Host "Successfully updated: $updated pages"

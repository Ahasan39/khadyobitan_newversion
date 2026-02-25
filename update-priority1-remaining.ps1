# Update Cart.tsx
Write-Host "Updating Cart.tsx..."
$content = Get-Content 'resources\js\Pages\Cart.tsx' -Raw

# Update imports
$content = $content -replace 'import \{ Link, useNavigate \} from "react-router-dom";', 'import { Head, Link, router } from "@inertiajs/react";'
$content = $content -replace 'import \{ Link \} from "react-router-dom";', 'import { Head, Link } from "@inertiajs/react";'

# Update Link to= to href=
$content = $content -replace 'Link to="/', 'Link href="/'
$content = $content -replace 'to=\{`/', 'href={`/'

# Replace useNavigate with router
$content = $content -replace 'const navigate = useNavigate\(\);', ''
$content = $content -replace 'navigate\(([^)]+)\);', 'router.visit($1);'

# Add Head component
if ($content -notmatch '<Head') {
    $content = $content -replace '  return \(\s+    <div', @'
  return (
    <>
      <Head title="Shopping Cart - Khadyobitan" />
      <div
'@
    $content = $content -replace '    </div>\s+  \);\s+\};\s+export default Cart;', @'
    </div>
    </>
  );
};
export default Cart;
'@
}

Set-Content 'resources\js\Pages\Cart.tsx' -Value $content
Write-Host "Cart.tsx updated!"

# Update Checkout.tsx
Write-Host "Updating Checkout.tsx..."
$content = Get-Content 'resources\js\Pages\Checkout.tsx' -Raw

# Update imports
$content = $content -replace 'import \{ Link, useNavigate \} from "react-router-dom";', 'import { Head, Link, router, useForm } from "@inertiajs/react";'
$content = $content -replace 'import \{ Link \} from "react-router-dom";', 'import { Head, Link } from "@inertiajs/react";'
$content = $content -replace 'import \{ useNavigate \} from "react-router-dom";', 'import { router } from "@inertiajs/react";'

# Update Link to= to href=
$content = $content -replace 'Link to="/', 'Link href="/'
$content = $content -replace 'to=\{`/', 'href={`/'

# Replace useNavigate with router
$content = $content -replace 'const navigate = useNavigate\(\);', ''
$content = $content -replace 'navigate\(([^)]+)\);', 'router.visit($1);'

# Add Head component
if ($content -notmatch '<Head') {
    $content = $content -replace '  return \(\s+    <div', @'
  return (
    <>
      <Head title="Checkout - Khadyobitan" />
      <div
'@
    $content = $content -replace '    </div>\s+  \);\s+\};\s+export default Checkout;', @'
    </div>
    </>
  );
};
export default Checkout;
'@
}

Set-Content 'resources\js\Pages\Checkout.tsx' -Value $content
Write-Host "Checkout.tsx updated!"

Write-Host "`nAll Priority 1 pages updated successfully!"
Write-Host "Updated: Cart.tsx, Checkout.tsx"

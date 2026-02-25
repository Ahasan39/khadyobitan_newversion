$content = Get-Content 'resources\js\Pages\ProductDetail.tsx' -Raw

# Update imports - remove React Router, add Inertia
$content = $content -replace 'import \{ useParams, Link, useNavigate \} from "react-router-dom";', 'import { Head, Link, router } from "@inertiajs/react";'

# Add props interface before the component
$content = $content -replace 'const ProductDetail = \(\) => \{', @'
interface ProductDetailProps {
  product?: any;
  relatedProducts?: any[];
  recentlyViewed?: any[];
}

const ProductDetail = ({ product: serverProduct, relatedProducts, recentlyViewed: serverRecentlyViewed }: ProductDetailProps) => {
'@

# Remove useParams and useNavigate, use serverProduct
$content = $content -replace '  const \{ t \} = useTranslation\(\);\s+  const \{ slug \} = useParams\(\);\s+  const navigate = useNavigate\(\);\s+  const product = products\.find\(\(p\) => p\.slug === slug\);', @'
  const { t } = useTranslation();
  const product = serverProduct || products.find((p) => p.slug === window.location.pathname.split('/').pop());
'@

# Update navigate to router.visit
$content = $content -replace 'navigate\("/checkout"\);', 'router.visit("/checkout");'

# Update Link to="/shop" to href="/shop"
$content = $content -replace 'Link to="/shop"', 'Link href="/shop"'
$content = $content -replace 'Link to="/"', 'Link href="/"'
$content = $content -replace 'Link to=\{`/shop\?category=', 'Link href={`/shop?category='

# Use server data for related and recently viewed
$content = $content -replace 'const related = products\.filter\(\(p\) => p\.category === product\.category && p\.id !== product\.id\)\.slice\(0, 4\);', @'
const related = relatedProducts || products.filter((p) => p.category === product.category && p.id !== product.id).slice(0, 4);
  const recentlyViewed = serverRecentlyViewed || products.filter((p) => recentlyViewedIds.includes(p.id) && p.slug !== product?.slug).slice(0, 4);
'@

# Remove the duplicate recentlyViewed definition
$content = $content -replace '  const recentlyViewed = products\.filter\(\(p\) => recentlyViewedIds\.includes\(p\.id\) && p\.slug !== slug\)\.slice\(0, 4\);', ''

# Add Head component
$content = $content -replace '  return \(\s+    <div className="bg-background min-h-screen">', @'
  return (
    <>
      <Head title={`${product?.name || "Product"} - Khadyobitan`} />
      <div className="bg-background min-h-screen">
'@

# Close fragment
$content = $content -replace '      </div>\s+    </div>\s+  \);\s+\};', @'
      </div>
    </div>
    </>
  );
};
'@

Set-Content 'resources\js\Pages\ProductDetail.tsx' -Value $content
Write-Host "ProductDetail.tsx updated successfully!"

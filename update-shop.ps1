$content = Get-Content 'resources\js\Pages\Shop.tsx' -Raw

# Add props interface and update function signature
$content = $content -replace 'const Shop = \(\) => \{', @'
interface ShopProps {
  products?: any[];
  categories?: any[];
  filters?: {
    category?: string;
    minPrice?: number;
    maxPrice?: number;
    sort?: string;
  };
}

const Shop = ({ products: serverProducts, categories: serverCategories, filters }: ShopProps) => {
'@

# Add display variables after useTranslation
$content = $content -replace '  const \{ t \} = useTranslation\(\);', @'
  const { t } = useTranslation();
  
  // Use server data if available, otherwise fall back to static data
  const displayProducts = serverProducts || products;
  const displayCategories = serverCategories || categories;
'@

# Update filtered useMemo to use displayProducts
$content = $content -replace 'let result = \[\.\.\.products\];', 'let result = [...displayProducts];'

# Update categories reference in filtered
$content = $content -replace 'p\.category === categories\.find', 'p.category === displayCategories.find'

# Update useMemo dependencies
$content = $content -replace '\}, \[selectedCategory, sortBy, priceRange, selectedWeights, selectedCerts\]\);', '}, [displayProducts, selectedCategory, sortBy, priceRange, selectedWeights, selectedCerts]);'

# Update categories.map to displayCategories.map
$content = $content -replace '\{categories\.map\(\(cat\) =>', '{displayCategories.map((cat) =>'

# Update state initialization with filters
$content = $content -replace 'const \[selectedCategory, setSelectedCategory\] = useState<string \| null>\(null\);', 'const [selectedCategory, setSelectedCategory] = useState<string | null>(filters?.category || null);'
$content = $content -replace 'const \[sortBy, setSortBy\] = useState\("featured"\);', 'const [sortBy, setSortBy] = useState(filters?.sort || "featured");'
$content = $content -replace 'const \[priceRange, setPriceRange\] = useState<\[number, number\]>\(\[0, 2000\]\);', @'
const [priceRange, setPriceRange] = useState<[number, number]>([
    filters?.minPrice || 0,
    filters?.maxPrice || 2000
  ]);
'@

# Add Head component
$content = $content -replace '  return \(\s+<div className="section-padding">', @'
  return (
    <>
      <Head title="Shop - Khadyobitan" />
      <div className="section-padding">
'@

# Close fragment
$content = $content -replace '      </div>\s+    </div>\s+  \);\s+\};', @'
      </div>
    </div>
    </>
  );
};
'@

Set-Content 'resources\js\Pages\Shop.tsx' -Value $content
Write-Host "Shop.tsx updated successfully!"

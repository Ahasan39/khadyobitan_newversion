import { useState, useMemo } from "react";
import { Head } from "@inertiajs/react";
import { motion } from "framer-motion";
import { SlidersHorizontal, X, ChevronDown } from "lucide-react";
import ProductCard from "@/components/ProductCard";
import { products, categories } from "@/data/products";
import { useTranslation } from "react-i18next";

const weightFilters = ["100g", "250g", "500g", "1kg", "2kg"];
const certFilters = ["Organic", "Non-GMO", "Gluten-Free", "Lab Tested", "Cold-Pressed"];

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
  const { t } = useTranslation();
  
  // Use server data if available, otherwise fall back to static data
  const displayProducts = serverProducts || products;
  const displayCategories = serverCategories || categories;
  const [selectedCategory, setSelectedCategory] = useState<string | null>(filters?.category || null);
  const [sortBy, setSortBy] = useState(filters?.sort || "featured");
  const [showFilters, setShowFilters] = useState(false);
  const [priceRange, setPriceRange] = useState<[number, number]>([
    filters?.minPrice || 0,
    filters?.maxPrice || 2000
  ]);
  const [selectedWeights, setSelectedWeights] = useState<string[]>([]);
  const [selectedCerts, setSelectedCerts] = useState<string[]>([]);

  const sortOptions = [
    { label: t("shop.featured"), value: "featured" },
    { label: t("shop.priceLowHigh"), value: "price-asc" },
    { label: t("shop.priceHighLow"), value: "price-desc" },
    { label: t("shop.bestRating"), value: "rating" },
    { label: t("shop.newestArrivals"), value: "newest" },
  ];

  const toggleFilter = (arr: string[], val: string, setter: (v: string[]) => void) => {
    setter(arr.includes(val) ? arr.filter((v) => v !== val) : [...arr, val]);
  };

  const activeFilterCount = (selectedCategory ? 1 : 0) + selectedWeights.length + selectedCerts.length + (priceRange[0] > 0 || priceRange[1] < 2000 ? 1 : 0);

  const clearAll = () => {
    setSelectedCategory(null);
    setPriceRange([0, 2000]);
    setSelectedWeights([]);
    setSelectedCerts([]);
  };

  const filtered = useMemo(() => {
    let result = [...displayProducts];
    if (selectedCategory) {
      result = result.filter(
        (p) =>
          p.category.toLowerCase().replace(/\s+/g, "-").replace(/&/g, "") === selectedCategory ||
          p.category === displayCategories.find((c) => c.slug === selectedCategory)?.name
      );
    }
    result = result.filter((p) => p.price >= priceRange[0] && p.price <= priceRange[1]);
    if (selectedWeights.length > 0) {
      result = result.filter((p) => selectedWeights.some((w) => p.weights.includes(w)));
    }
    if (selectedCerts.length > 0) {
      result = result.filter((p) => selectedCerts.some((c) => p.badges.includes(c)));
    }
    switch (sortBy) {
      case "price-asc": result.sort((a, b) => a.price - b.price); break;
      case "price-desc": result.sort((a, b) => b.price - a.price); break;
      case "rating": result.sort((a, b) => b.rating - a.rating); break;
      case "newest": result.sort((a, b) => b.id - a.id); break;
    }
    return result;
  }, [displayProducts, selectedCategory, sortBy, priceRange, selectedWeights, selectedCerts]);

  const FilterSidebar = () => (
    <>
      <div className="mb-6">
        <h3 className="font-heading text-base font-semibold mb-3">{t("categories.title")}</h3>
        <div className="space-y-1">
          <button onClick={() => setSelectedCategory(null)} className={`w-full text-left px-3 py-2 rounded-lg text-sm font-body transition-colors ${!selectedCategory ? "bg-primary text-primary-foreground" : "hover:bg-muted"}`}>
            {t("shop.allProducts")}
          </button>
          {displayCategories.map((cat) => (
            <button key={cat.slug} onClick={() => setSelectedCategory(cat.slug === selectedCategory ? null : cat.slug)} className={`w-full text-left px-3 py-2 rounded-lg text-sm font-body transition-colors ${selectedCategory === cat.slug ? "bg-primary text-primary-foreground" : "hover:bg-muted"}`}>
              {cat.name}<span className="text-xs opacity-60 ml-1">({cat.count})</span>
            </button>
          ))}
        </div>
      </div>

      <div className="mb-6">
        <h3 className="font-heading text-base font-semibold mb-3">{t("shop.priceRange")}</h3>
        <div className="px-1">
          <input type="range" min={0} max={2000} step={50} value={priceRange[1]} onChange={(e) => setPriceRange([priceRange[0], Number(e.target.value)])} className="w-full accent-[hsl(var(--primary))]" />
          <div className="flex justify-between font-body text-xs text-muted-foreground mt-1">
            <span>৳{priceRange[0]}</span><span>৳{priceRange[1]}</span>
          </div>
        </div>
      </div>

      <div className="mb-6">
        <h3 className="font-heading text-base font-semibold mb-3">{t("shop.weight")}</h3>
        <div className="space-y-2">
          {weightFilters.map((w) => (
            <label key={w} className="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" checked={selectedWeights.includes(w)} onChange={() => toggleFilter(selectedWeights, w, setSelectedWeights)} className="accent-[hsl(var(--primary))] rounded" />
              <span className="font-body text-sm">{w}</span>
            </label>
          ))}
        </div>
      </div>

      <div className="mb-6">
        <h3 className="font-heading text-base font-semibold mb-3">{t("shop.certifications")}</h3>
        <div className="space-y-2">
          {certFilters.map((c) => (
            <label key={c} className="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" checked={selectedCerts.includes(c)} onChange={() => toggleFilter(selectedCerts, c, setSelectedCerts)} className="accent-[hsl(var(--primary))] rounded" />
              <span className="font-body text-sm">{c}</span>
            </label>
          ))}
        </div>
      </div>

      {activeFilterCount > 0 && (
        <button onClick={clearAll} className="w-full py-2 text-sm font-body font-medium text-destructive hover:underline">
          {t("shop.clearAllFilters")}
        </button>
      )}
    </>
  );

  return (
    <>
      <Head title="Shop - Khadyobitan" />
      <div className="section-padding">
      <div className="container-custom">
        <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="mb-8">
          <p className="font-accent text-xl text-accent mb-1">{t("shop.ourCollection")}</p>
          <h1 className="font-heading text-3xl sm:text-4xl font-bold text-foreground">{t("shop.organicProducts")}</h1>
        </motion.div>

        <div className="flex gap-8">
          <aside className="hidden lg:block w-60 shrink-0"><FilterSidebar /></aside>

          <div className="flex-1">
            <div className="flex items-center justify-between mb-6 pb-4 border-b border-border">
              <p className="font-body text-sm text-muted-foreground">
                {t("shop.showing")} <span className="font-semibold text-foreground">{filtered.length}</span> {t("shop.products")}
              </p>
              <div className="flex items-center gap-3">
                <button onClick={() => setShowFilters(!showFilters)} className="lg:hidden flex items-center gap-1.5 text-sm font-body font-medium text-foreground relative">
                  <SlidersHorizontal className="h-4 w-4" /> {t("shop.filters")}
                  {activeFilterCount > 0 && <span className="bg-primary text-primary-foreground text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center">{activeFilterCount}</span>}
                </button>
                <select value={sortBy} onChange={(e) => setSortBy(e.target.value)} className="text-sm font-body bg-muted border-0 rounded-lg px-3 py-2 text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30">
                  {sortOptions.map((o) => <option key={o.value} value={o.value}>{o.label}</option>)}
                </select>
              </div>
            </div>

            {activeFilterCount > 0 && (
              <div className="flex items-center flex-wrap gap-2 mb-4">
                <span className="font-body text-xs text-muted-foreground">{t("shop.active")}</span>
                {selectedCategory && (
                  <span className="inline-flex items-center gap-1 bg-primary/10 text-primary text-xs font-body font-medium px-2.5 py-1 rounded-full">
                    {categories.find((c) => c.slug === selectedCategory)?.name}
                    <button onClick={() => setSelectedCategory(null)}><X className="h-3 w-3" /></button>
                  </span>
                )}
                {(priceRange[0] > 0 || priceRange[1] < 2000) && (
                  <span className="inline-flex items-center gap-1 bg-primary/10 text-primary text-xs font-body font-medium px-2.5 py-1 rounded-full">
                    ৳{priceRange[0]} - ৳{priceRange[1]}
                    <button onClick={() => setPriceRange([0, 2000])}><X className="h-3 w-3" /></button>
                  </span>
                )}
                {selectedWeights.map((w) => (
                  <span key={w} className="inline-flex items-center gap-1 bg-primary/10 text-primary text-xs font-body font-medium px-2.5 py-1 rounded-full">
                    {w}<button onClick={() => toggleFilter(selectedWeights, w, setSelectedWeights)}><X className="h-3 w-3" /></button>
                  </span>
                ))}
                {selectedCerts.map((c) => (
                  <span key={c} className="inline-flex items-center gap-1 bg-primary/10 text-primary text-xs font-body font-medium px-2.5 py-1 rounded-full">
                    {c}<button onClick={() => toggleFilter(selectedCerts, c, setSelectedCerts)}><X className="h-3 w-3" /></button>
                  </span>
                ))}
                <button onClick={clearAll} className="text-xs font-body text-destructive hover:underline ml-1">{t("shop.clearAll")}</button>
              </div>
            )}

            {showFilters && (
              <motion.div initial={{ height: 0, opacity: 0 }} animate={{ height: "auto", opacity: 1 }} className="lg:hidden mb-6 p-4 bg-muted rounded-xl overflow-hidden">
                <FilterSidebar />
              </motion.div>
            )}

            <div className="grid grid-cols-2 md:grid-cols-3 gap-4 lg:gap-6">
              {filtered.map((product, i) => <ProductCard key={product.id} product={product} index={i} />)}
            </div>

            {filtered.length === 0 && (
              <div className="text-center py-20">
                <p className="font-heading text-xl text-muted-foreground">{t("shop.noProductsFound")}</p>
                <button onClick={clearAll} className="font-body text-sm text-primary mt-2 hover:underline">{t("shop.clearFilters")}</button>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
    </>
  );
};

export default Shop;


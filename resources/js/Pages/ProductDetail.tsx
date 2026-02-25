import { useState, useEffect } from "react";
import { useParams, Link, useNavigate } from "react-router-dom";
import { motion } from "framer-motion";
import {
  Star, Heart, ShoppingCart, Check, Minus, Plus, ChevronRight, Truck, Shield,
  Leaf, FlaskConical, Package, BookOpen, Thermometer, Award, RotateCcw, MessageCircle, Phone,
} from "lucide-react";
import { products } from "@/data/products";
import { productImageMap } from "@/data/productImages";
import { useCartStore } from "@/store/cartStore";
import ProductCard from "@/components/ProductCard";
import ReviewsTab from "@/components/ReviewsTab";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

const ProductDetail = () => {
  const { t } = useTranslation();
  const { slug } = useParams();
  const navigate = useNavigate();
  const product = products.find((p) => p.slug === slug);
  const [selectedWeight, setSelectedWeight] = useState(product?.weight || "");
  const [quantity, setQuantity] = useState(1);
  const [activeTab, setActiveTab] = useState("Description");
  const [selectedImage, setSelectedImage] = useState(0);
  const addItem = useCartStore((s) => s.addItem);
  const toggleWishlist = useCartStore((s) => s.toggleWishlist);
  const wishlist = useCartStore((s) => s.wishlist);

  const allTabs = [t("product.description"), t("product.reviews")];

  useEffect(() => {
    if (product) {
      const stored = JSON.parse(localStorage.getItem("recentlyViewed") || "[]") as number[];
      const updated = [product.id, ...stored.filter((id) => id !== product.id)].slice(0, 8);
      localStorage.setItem("recentlyViewed", JSON.stringify(updated));
    }
  }, [product]);

  // Reset active tab when language changes
  useEffect(() => {
    setActiveTab(t("product.description"));
  }, [t]);

  const recentlyViewedIds: number[] = JSON.parse(localStorage.getItem("recentlyViewed") || "[]");
  const recentlyViewed = products.filter((p) => recentlyViewedIds.includes(p.id) && p.slug !== slug).slice(0, 4);

  if (!product) {
    return (
      <div className="section-padding text-center">
        <p className="font-heading text-2xl">{t("product.notFound")}</p>
        <Link to="/shop" className="font-body text-primary hover:underline mt-2 inline-block">{t("product.backToShop")}</Link>
      </div>
    );
  }

  const isWished = wishlist.includes(product.id);
  const discount = product.oldPrice ? Math.round(((product.oldPrice - product.price) / product.oldPrice) * 100) : 0;
  const related = products.filter((p) => p.category === product.category && p.id !== product.id).slice(0, 4);

  const handleAddToCart = () => {
    addItem(product, selectedWeight, quantity);
    toast.success(t("product.addedToCart", { name: product.name }));
  };

  const handleBuyNow = () => {
    addItem(product, selectedWeight, quantity);
    navigate("/checkout");
  };

  return (
    <div className="bg-background min-h-screen">
      {/* Breadcrumb */}
      <div className="border-b border-border/50 bg-muted/30">
        <div className="container-custom py-3">
          <nav className="flex items-center gap-1.5 text-xs font-body text-muted-foreground">
            <Link to="/" className="hover:text-primary transition-colors">{t("common.home")}</Link>
            <ChevronRight className="h-3 w-3" />
            <Link to="/shop" className="hover:text-primary transition-colors">{t("nav.shop")}</Link>
            <ChevronRight className="h-3 w-3" />
            <Link to={`/shop?category=${product.category.toLowerCase().replace(/\s+/g, "-").replace(/&/g, "")}`} className="hover:text-primary transition-colors">{product.category}</Link>
            <ChevronRight className="h-3 w-3" />
            <span className="text-foreground font-medium">{product.name}</span>
          </nav>
        </div>
      </div>

      <div className="container-custom py-8 lg:py-12">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16">
          {/* Image Gallery */}
          <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }} className="lg:col-span-7">
            <div className="sticky top-24">
              <div className="aspect-[4/3] bg-muted rounded-2xl relative overflow-hidden group border border-border/40">
                <img src={productImageMap[product.slug] || "/placeholder.svg"} alt={product.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                {discount > 0 && (
                  <span className="absolute top-4 left-4 bg-accent text-accent-foreground text-xs font-bold px-3 py-1 rounded-lg font-body shadow-sm">-{discount}%</span>
                )}
                {product.badges.slice(0, 1).map((b) => (
                  <span key={b} className="absolute top-4 right-4 bg-primary text-primary-foreground text-xs font-bold px-3 py-1 rounded-lg font-body shadow-sm">{b}</span>
                ))}
              </div>
              <div className="flex gap-2.5 mt-3">
                {[0, 1, 2, 3].map((i) => (
                  <button key={i} onClick={() => setSelectedImage(i)} className={`flex-1 aspect-square max-w-[80px] rounded-xl bg-muted overflow-hidden border-2 transition-all duration-200 hover:border-primary/50 ${selectedImage === i ? "border-primary shadow-md" : "border-transparent"}`}>
                    <img src={productImageMap[product.slug] || "/placeholder.svg"} alt={product.name} className="w-full h-full object-cover" />
                  </button>
                ))}
              </div>
            </div>
          </motion.div>

          {/* Product Info */}
          <motion.div initial={{ opacity: 0, x: 20 }} animate={{ opacity: 1, x: 0 }} transition={{ delay: 0.15 }} className="lg:col-span-5">
            <div className="flex flex-wrap gap-1.5 mb-3">
              {product.badges.map((b) => (
                <span key={b} className="bg-primary/8 text-primary text-[10px] font-bold font-body px-2.5 py-1 rounded-full border border-primary/15">{b}</span>
              ))}
            </div>

            <p className="font-body text-[11px] text-muted-foreground uppercase tracking-[0.15em] mb-1">{product.category}</p>
            <h1 className="font-heading text-2xl sm:text-3xl lg:text-[28px] font-bold text-foreground mb-3 leading-tight">{product.name}</h1>

            <div className="flex items-center gap-2 mb-5">
              <div className="flex gap-0.5">
                {Array.from({ length: 5 }).map((_, i) => (
                  <Star key={i} className={`h-4 w-4 ${i < Math.floor(product.rating) ? "text-accent fill-accent" : "text-border"}`} />
                ))}
              </div>
              <span className="font-body text-sm text-muted-foreground">
                {product.rating} ({product.reviewsCount} {t("product.reviews")})
              </span>
            </div>

            <div className="flex items-baseline gap-3 mb-5 pb-5 border-b border-border/50">
              <span className="font-heading text-3xl font-bold text-foreground">৳{product.price}</span>
              {product.oldPrice && (
                <>
                  <span className="font-body text-base text-muted-foreground line-through">৳{product.oldPrice}</span>
                  <span className="bg-accent/15 text-accent-foreground text-xs font-bold font-body px-2 py-0.5 rounded-md">
                    {t("product.save")} {discount}%
                  </span>
                </>
              )}
            </div>

            <p className={`font-body text-sm mb-5 flex items-center gap-1.5 ${product.stock > 50 ? "text-primary" : "text-accent"}`}>
              <Check className="h-4 w-4" />
              {product.stock > 50 ? t("product.inStock") : t("product.onlyLeft", { count: product.stock })}
            </p>

            <div className="mb-5">
              <p className="font-body text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-2">{t("product.weightSize")}</p>
              <div className="flex flex-wrap gap-2">
                {product.weights.map((w) => (
                  <button key={w} onClick={() => setSelectedWeight(w)} className={`px-4 py-2 rounded-lg text-sm font-body font-medium border-2 transition-all duration-200 ${selectedWeight === w ? "border-primary bg-primary text-primary-foreground shadow-sm" : "border-border hover:border-primary/50 text-foreground"}`}>
                    {w}
                  </button>
                ))}
              </div>
            </div>

            <div className="flex items-center gap-2.5 mb-4">
              <div className="flex items-center border-2 border-border rounded-lg bg-card">
                <button onClick={() => setQuantity(Math.max(1, quantity - 1))} className="p-2.5 hover:bg-muted transition-colors rounded-l-md"><Minus className="h-4 w-4" /></button>
                <span className="w-10 text-center font-body font-semibold text-sm">{quantity}</span>
                <button onClick={() => setQuantity(quantity + 1)} className="p-2.5 hover:bg-muted transition-colors rounded-r-md"><Plus className="h-4 w-4" /></button>
              </div>
              <button onClick={() => toggleWishlist(product.id)} className={`p-3 rounded-lg border-2 transition-all duration-200 ${isWished ? "border-accent bg-accent/10 text-accent" : "border-border hover:border-accent text-muted-foreground hover:text-accent"}`} aria-label="Toggle wishlist">
                <Heart className={`h-5 w-5 ${isWished ? "fill-current" : ""}`} />
              </button>
            </div>

            <div className="grid grid-cols-2 gap-2.5 mb-6">
              <button onClick={handleAddToCart} className="py-3 bg-primary text-primary-foreground rounded-lg font-body font-semibold text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-all duration-200 shadow-sm hover:shadow-md">
                <ShoppingCart className="h-4 w-4" /> {t("product.addToCart")}
              </button>
              <button onClick={handleBuyNow} className="py-3 bg-secondary text-secondary-foreground rounded-lg font-body font-semibold text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-all duration-200 shadow-sm hover:shadow-md">
                <Package className="h-4 w-4" /> {t("product.orderNow")}
              </button>
              <a href="https://m.me/your-page" target="_blank" rel="noopener noreferrer" className="py-3 bg-messenger text-white rounded-lg font-body font-semibold text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-all duration-200 shadow-sm hover:shadow-md">
                <MessageCircle className="h-4 w-4" /> {t("product.chatWithUs")}
              </a>
              <a href="https://wa.me/8801XXXXXXXXX" target="_blank" rel="noopener noreferrer" className="py-3 bg-whatsapp text-white rounded-lg font-body font-semibold text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-all duration-200 shadow-sm hover:shadow-md">
                <Phone className="h-4 w-4" /> {t("product.whatsappUs")}
              </a>
            </div>

            <div className="grid grid-cols-2 gap-2.5 p-4 bg-muted/60 rounded-xl border border-border/40 mb-5">
              {[
                { icon: Leaf, text: t("product.organicCertified") },
                { icon: Shield, text: t("product.noPreservatives") },
                { icon: FlaskConical, text: t("product.labTested") },
                { icon: Truck, text: t("product.freeShipping") },
              ].map(({ icon: Icon, text }) => (
                <div key={text} className="flex items-center gap-2 py-1">
                  <div className="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <Icon className="h-3.5 w-3.5 text-primary" />
                  </div>
                  <span className="font-body text-[11px] text-foreground leading-tight">{text}</span>
                </div>
              ))}
            </div>

            <p className="font-body text-xs text-muted-foreground">
              <span className="font-semibold text-foreground">{t("product.origin")}:</span> {product.origin}
            </p>
          </motion.div>
        </div>

        {/* Tabs Section */}
        <div className="mt-16 lg:mt-20">
          <div className="flex border-b border-border overflow-x-auto scrollbar-hide gap-1">
            {allTabs.map((tab) => (
              <button
                key={tab}
                onClick={() => setActiveTab(tab)}
                className={`px-5 py-3 font-body text-sm font-medium whitespace-nowrap transition-all duration-200 border-b-2 rounded-t-lg ${
                  activeTab === tab
                    ? "border-primary text-primary bg-primary/5"
                    : "border-transparent text-muted-foreground hover:text-foreground hover:bg-muted/50"
                }`}
              >
                {tab}
              </button>
            ))}
          </div>

          <div className="py-8">
            {activeTab === t("product.description") && (
              <p className="font-body text-sm text-muted-foreground leading-relaxed max-w-3xl">{product.description}</p>
            )}
            {activeTab === t("product.reviews") && (
              <ReviewsTab productId={product.id} rating={product.rating} reviewsCount={product.reviewsCount} />
            )}
          </div>
        </div>

        {/* Related Products */}
        {related.length > 0 && (
          <div className="mt-16 pt-12 border-t border-border/50">
            <h2 className="font-heading text-2xl font-bold mb-6">{t("product.youMayAlsoLike")}</h2>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-5">
              {related.map((p, i) => (
                <ProductCard key={p.id} product={p} index={i} />
              ))}
            </div>
          </div>
        )}

        {/* Recently Viewed */}
        {recentlyViewed.length > 0 && (
          <div className="mt-16 pt-12 border-t border-border/50">
            <h2 className="font-heading text-2xl font-bold mb-6">{t("product.recentlyViewed")}</h2>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-5">
              {recentlyViewed.map((p, i) => (
                <ProductCard key={p.id} product={p} index={i} />
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default ProductDetail;

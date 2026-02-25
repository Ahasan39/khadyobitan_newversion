import { Link } from "react-router-dom";
import { Heart, ShoppingCart, Star, Eye } from "lucide-react";
import { motion } from "framer-motion";
import type { Product } from "@/data/products";
import { useCartStore } from "@/store/cartStore";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";
import { productImageMap } from "@/data/productImages";

interface ProductCardProps {
  product: Product;
  index?: number;
}

const ProductCard = ({ product, index = 0 }: ProductCardProps) => {
  const { t } = useTranslation();
  const addItem = useCartStore((s) => s.addItem);
  const toggleWishlist = useCartStore((s) => s.toggleWishlist);
  const wishlist = useCartStore((s) => s.wishlist);
  const isWished = wishlist.includes(product.id);

  const discount = product.oldPrice
    ? Math.round(((product.oldPrice - product.price) / product.oldPrice) * 100)
    : 0;

  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault();
    addItem(product, product.weight);
    toast.success(t("product.addedToCart", { name: product.name }));
  };

  return (
    <motion.div
      initial={{ opacity: 0, y: 24 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{ delay: index * 0.06, duration: 0.45, ease: "easeOut" }}
    >
      <Link
        to={`/product/${product.slug}`}
        className="group block bg-card rounded-2xl overflow-hidden border border-border/60 hover:border-primary/30 hover:shadow-xl hover:shadow-primary/5 transition-all duration-500"
      >
        <div className="relative aspect-[4/3.5] bg-muted overflow-hidden">
          <img src={productImageMap[product.slug] || "/placeholder.svg"} alt={product.name} className="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" />
          <div className="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
            {discount > 0 && (
              <span className="bg-accent text-accent-foreground text-[10px] font-bold px-2 py-0.5 rounded-md font-body shadow-sm">-{discount}%</span>
            )}
            {product.badges.slice(0, 1).map((b) => (
              <span key={b} className="bg-primary text-primary-foreground text-[10px] font-bold px-2 py-0.5 rounded-md font-body shadow-sm">{b}</span>
            ))}
          </div>
          <div className="absolute top-2.5 right-2.5 flex flex-col gap-1.5 opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all duration-300">
            <button onClick={(e) => { e.preventDefault(); toggleWishlist(product.id); }} className={`p-2 rounded-full shadow-md backdrop-blur-sm transition-all duration-200 ${isWished ? "bg-accent text-accent-foreground scale-110" : "bg-card/90 text-foreground hover:bg-accent hover:text-accent-foreground"}`} aria-label="Toggle wishlist">
              <Heart className={`h-3.5 w-3.5 ${isWished ? "fill-current" : ""}`} />
            </button>
            <button onClick={(e) => e.preventDefault()} className="p-2 rounded-full shadow-md bg-card/90 text-foreground hover:bg-primary hover:text-primary-foreground backdrop-blur-sm transition-all duration-200" aria-label="Quick view">
              <Eye className="h-3.5 w-3.5" />
            </button>
          </div>
          <div className="absolute bottom-0 left-0 right-0 p-2.5 opacity-0 group-hover:opacity-100 translate-y-3 group-hover:translate-y-0 transition-all duration-300">
            <button onClick={handleAddToCart} className="w-full py-2 bg-primary text-primary-foreground rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 hover:opacity-90 transition-opacity font-body shadow-lg">
              <ShoppingCart className="h-3.5 w-3.5" /> {t("product.addToCart")}
            </button>
          </div>
        </div>
        <div className="p-3.5 pt-3">
          <p className="text-[10px] font-body text-muted-foreground uppercase tracking-widest mb-0.5">{product.category}</p>
          <h3 className="font-heading text-[13px] font-semibold text-foreground line-clamp-1 mb-0.5 group-hover:text-primary transition-colors duration-300">{product.name}</h3>
          <p className="text-[11px] text-muted-foreground font-body mb-2">{product.weight}</p>
          <div className="flex items-center gap-0.5 mb-2">
            {Array.from({ length: 5 }).map((_, i) => (
              <Star key={i} className={`h-3 w-3 ${i < Math.floor(product.rating) ? "text-accent fill-accent" : "text-border"}`} />
            ))}
            <span className="text-[10px] text-muted-foreground font-body ml-1">({product.reviewsCount})</span>
          </div>
          <div className="flex items-baseline gap-2">
            <span className="font-heading text-base font-bold text-foreground">৳{product.price}</span>
            {product.oldPrice && (
              <span className="text-xs text-muted-foreground line-through font-body">৳{product.oldPrice}</span>
            )}
          </div>
        </div>
      </Link>
    </motion.div>
  );
};

export default ProductCard;

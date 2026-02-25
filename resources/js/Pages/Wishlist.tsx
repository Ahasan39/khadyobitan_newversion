import { Head, Link } from "@inertiajs/react";
import { motion } from "framer-motion";
import { Heart, ArrowRight } from "lucide-react";
import { useCartStore } from "@/store/cartStore";
import { products } from "@/data/products";
import ProductCard from "@/components/ProductCard";
import { useTranslation } from "react-i18next";

const Wishlist = () => {
  const { t } = useTranslation();
  const wishlist = useCartStore((s) => s.wishlist);
  const wishedProducts = products.filter((p) => wishlist.includes(p.id));

  if (wishedProducts.length === 0) {
    return (
    <>
      <Head title="Wishlist - Khadyobitan" />
      <div className="section-padding text-center">
        <div className="container-custom max-w-md mx-auto">
          <Heart className="h-16 w-16 text-muted-foreground mx-auto mb-4" />
          <h1 className="font-heading text-2xl font-bold mb-2">{t("wishlist.emptyTitle")}</h1>
          <p className="font-body text-sm text-muted-foreground mb-6">{t("wishlist.emptyDesc")}</p>
          <Link href="/shop" className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity">
            {t("wishlist.browseProducts")} <ArrowRight className="h-4 w-4" />
          </Link>
        </div>
      </div>
    );
  }

  return (
    <>
      <Head title="Wishlist - Khadyobitan" />
      <div className="section-padding">
      <div className="container-custom">
        <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }}>
          <h1 className="font-heading text-3xl font-bold mb-2">{t("wishlist.myWishlist")}</h1>
          <p className="font-body text-sm text-muted-foreground mb-8">
            {wishedProducts.length !== 1 ? t("wishlist.savedItemsPlural", { count: wishedProducts.length }) : t("wishlist.savedItems", { count: wishedProducts.length })}
          </p>
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
            {wishedProducts.map((product, i) => (
              <ProductCard key={product.id} product={product} index={i} />
            ))}
          </div>
        </motion.div>
      </div>
    </div>
    </>
  );
};
export default Wishlist;


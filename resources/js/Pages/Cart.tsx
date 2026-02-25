import { useState } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { Minus, Plus, X, ShoppingCart, ArrowRight, Truck, Shield, RotateCcw, Tag } from "lucide-react";
import { useCartStore } from "@/store/cartStore";
import ProductCard from "@/components/ProductCard";
import { products, coupons } from "@/data/products";
import { toast } from "sonner";
import { productImageMap } from "@/data/productImages";
import { useTranslation } from "react-i18next";

const Cart = () => {
  const { t } = useTranslation();
  const { items, removeItem, updateQuantity, subtotal, clearCart } = useCartStore();
  const sub = subtotal();
  const shipping = sub >= 1000 ? 0 : 60;

  const [couponCode, setCouponCode] = useState("");
  const [appliedCoupon, setAppliedCoupon] = useState<string | null>(null);

  const couponData = appliedCoupon ? coupons[appliedCoupon] : null;
  const discount = couponData
    ? couponData.type === "percent"
      ? Math.round(sub * couponData.discount / 100)
      : couponData.discount
    : 0;
  const total = sub + shipping - discount;

  const handleApplyCoupon = () => {
    const code = couponCode.trim().toUpperCase();
    const coupon = coupons[code];
    if (!coupon) { toast.error(t("cart.invalidCoupon")); return; }
    if (sub < coupon.minOrder) { toast.error(t("cart.minOrder", { amount: coupon.minOrder })); return; }
    setAppliedCoupon(code);
    const savings = coupon.type === "percent" ? Math.round(sub * coupon.discount / 100) : coupon.discount;
    toast.success(t("cart.couponApplied", { code, savings }));
  };

  const removeCoupon = () => { setAppliedCoupon(null); setCouponCode(""); toast.info(t("cart.couponRemoved")); };

  const recommended = products.filter((p) => !items.some((i) => i.product.id === p.id)).slice(0, 4);

  if (items.length === 0) {
    return (
      <div className="section-padding text-center">
        <div className="container-custom max-w-md mx-auto">
          <ShoppingCart className="h-16 w-16 text-muted-foreground mx-auto mb-4" />
          <h1 className="font-heading text-2xl font-bold mb-2">{t("cart.emptyTitle")}</h1>
          <p className="font-body text-sm text-muted-foreground mb-6">{t("cart.emptyDesc")}</p>
          <Link to="/shop" className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity">
            {t("cart.continueShopping")} <ArrowRight className="h-4 w-4" />
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="section-padding">
      <div className="container-custom">
        <motion.div initial={{ opacity: 0 }} animate={{ opacity: 1 }}>
          <h1 className="font-heading text-3xl font-bold mb-8">{t("cart.shoppingCart")}</h1>
          <div className="flex flex-col lg:flex-row gap-8">
            <div className="flex-1 space-y-4">
              {items.map((item) => (
                <div key={item.product.id} className="flex gap-4 p-4 bg-card border border-border rounded-xl">
                  <div className="w-20 h-20 bg-muted rounded-lg overflow-hidden shrink-0">
                    <img src={productImageMap[item.product.slug] || "/placeholder.svg"} alt={item.product.name} className="w-full h-full object-cover" />
                  </div>
                  <div className="flex-1 min-w-0">
                    <div className="flex items-start justify-between">
                      <div>
                        <Link to={`/product/${item.product.slug}`} className="font-heading text-sm font-semibold hover:text-primary transition-colors line-clamp-1">{item.product.name}</Link>
                        <p className="font-body text-xs text-muted-foreground">{item.selectedWeight}</p>
                      </div>
                      <button onClick={() => removeItem(item.product.id)} className="p-1 text-muted-foreground hover:text-destructive transition-colors" aria-label="Remove"><X className="h-4 w-4" /></button>
                    </div>
                    <div className="flex items-center justify-between mt-3">
                      <div className="flex items-center border border-border rounded-lg">
                        <button onClick={() => updateQuantity(item.product.id, item.quantity - 1)} className="p-1.5 hover:bg-muted transition-colors"><Minus className="h-3 w-3" /></button>
                        <span className="w-8 text-center text-sm font-body font-medium">{item.quantity}</span>
                        <button onClick={() => updateQuantity(item.product.id, item.quantity + 1)} className="p-1.5 hover:bg-muted transition-colors"><Plus className="h-3 w-3" /></button>
                      </div>
                      <span className="font-heading text-base font-bold">৳{item.product.price * item.quantity}</span>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            <div className="lg:w-80 shrink-0">
              <div className="bg-card border border-border rounded-xl p-6 sticky top-28">
                <h3 className="font-heading text-lg font-semibold mb-4">{t("cart.orderSummary")}</h3>
                <div className="space-y-3 font-body text-sm">
                  <div className="flex justify-between"><span className="text-muted-foreground">{t("cart.subtotal")}</span><span>৳{sub}</span></div>
                  <div className="flex justify-between">
                    <span className="text-muted-foreground">{t("cart.shipping")}</span>
                    <span className={shipping === 0 ? "text-primary font-medium" : ""}>{shipping === 0 ? t("cart.free") : `৳${shipping}`}</span>
                  </div>
                  {shipping > 0 && <p className="text-xs text-accent">{t("cart.addMoreForFree", { amount: 1000 - sub })}</p>}
                  {discount > 0 && (
                    <div className="flex justify-between text-primary">
                      <span className="flex items-center gap-1"><Tag className="h-3 w-3" /> {t("cart.discount")}</span>
                      <span>-৳{discount}</span>
                    </div>
                  )}
                  <div className="border-t border-border pt-3 flex justify-between font-heading text-lg font-bold">
                    <span>{t("cart.total")}</span><span>৳{total}</span>
                  </div>
                </div>

                <div className="mt-4 pt-4 border-t border-border">
                  {appliedCoupon ? (
                    <div className="flex items-center justify-between bg-primary/10 px-3 py-2 rounded-lg">
                      <div className="flex items-center gap-2"><Tag className="h-4 w-4 text-primary" /><span className="font-body text-sm font-medium text-primary">{appliedCoupon}</span></div>
                      <button onClick={removeCoupon} className="text-muted-foreground hover:text-destructive"><X className="h-4 w-4" /></button>
                    </div>
                  ) : (
                    <div className="flex gap-2">
                      <input type="text" value={couponCode} onChange={(e) => setCouponCode(e.target.value)} placeholder={t("cart.couponCode")} className="flex-1 px-3 py-2 rounded-lg border border-border bg-background text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" />
                      <button onClick={handleApplyCoupon} className="px-4 py-2 bg-muted text-foreground rounded-lg text-sm font-body font-medium hover:bg-muted/80 transition-colors">{t("cart.apply")}</button>
                    </div>
                  )}
                </div>

                <Link to="/checkout" className="w-full mt-6 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-opacity">
                  {t("cart.proceedToCheckout")} <ArrowRight className="h-4 w-4" />
                </Link>
                <Link to="/shop" className="block text-center mt-3 font-body text-sm text-primary hover:underline">{t("cart.continueShopping")}</Link>

                <div className="mt-6 pt-4 border-t border-border space-y-2">
                  {[
                    { icon: Shield, text: t("cart.secureCheckout") },
                    { icon: Truck, text: t("cart.freeDelivery") },
                    { icon: RotateCcw, text: t("cart.easyReturns") },
                  ].map(({ icon: Icon, text }) => (
                    <div key={text} className="flex items-center gap-2"><Icon className="h-3.5 w-3.5 text-primary" /><span className="font-body text-xs text-muted-foreground">{text}</span></div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </motion.div>

        {recommended.length > 0 && (
          <div className="mt-16">
            <h2 className="font-heading text-2xl font-bold mb-6">{t("cart.completeOrder")}</h2>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
              {recommended.map((p, i) => <ProductCard key={p.id} product={p} index={i} />)}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default Cart;

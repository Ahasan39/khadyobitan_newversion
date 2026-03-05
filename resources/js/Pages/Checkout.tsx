import React from 'react';
import { useState } from "react";
import { Head, Link, router } from "@inertiajs/react";
import { ChevronRight, CreditCard, Banknote, Smartphone, Building2, ShieldCheck } from "lucide-react";
import { useCartStore } from "@/store/cartStore";
import { toast } from "sonner";
import { getProductImageSrc } from "@/utils/imageUtils";
import { useTranslation } from "react-i18next";
import MainLayout from "@/Components/layout/MainLayout";
import axios from "axios";

interface CheckoutProps {
  cartItems?: any;
  shippingCharges?: any[];
  districts?: any[];
  customer?: any;
  subtotal?: number;
  shippingCost?: number;
  total?: number;
}

const districtsList = [
  "Dhaka", "Chittagong", "Rajshahi", "Khulna", "Sylhet", "Barisal", "Rangpur", "Mymensingh",
  "Comilla", "Gazipur", "Narayanganj",
];

const Checkout = ({ cartItems: serverCartItems, customer, districts: serverDistricts }: CheckoutProps) => {
  const { t } = useTranslation();
  
  const { items: localItems, subtotal: localSubtotal, clearCart } = useCartStore();
  
  // Use server cart if available, otherwise use local cart
  const hasServerCart = serverCartItems && Object.keys(serverCartItems).length > 0;
  const items = hasServerCart 
    ? Object.values(serverCartItems).map((item: any) => ({
        product: { id: item.id, name: item.name, price: item.price, image: item.options?.image },
        quantity: item.qty,
        selectedWeight: item.options?.weight || 'Default',
      }))
    : localItems;
  
  const sub = hasServerCart 
    ? Object.values(serverCartItems).reduce((acc: number, item: any) => acc + (item.price * item.qty), 0)
    : localSubtotal();
  
  const [payment, setPayment] = useState("cod");
  const [agreed, setAgreed] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [form, setForm] = useState({ 
    name: customer?.name || "", 
    phone: customer?.phone || "", 
    email: customer?.email || "", 
    address: customer?.address || "", 
    district: "", 
    area: "",
    notes: "" 
  });

  const paymentMethods = [
    { id: "cod", label: t("checkout.cod"), icon: Banknote, desc: t("checkout.codDesc") },
    { id: "bkash", label: t("checkout.bkash"), icon: Smartphone, desc: t("checkout.bkashDesc") },
    { id: "nagad", label: t("checkout.nagad"), icon: Smartphone, desc: t("checkout.nagadDesc") },
    { id: "card", label: t("checkout.card"), icon: CreditCard, desc: t("checkout.cardDesc") },
    { id: "bank", label: t("checkout.bank"), icon: Building2, desc: t("checkout.bankDesc") },
  ];

  const shippingCost = sub >= 1000 ? 0 : 60;
  const total = sub + shippingCost;
  const updateField = (field: string, value: string) => setForm((f) => ({ ...f, [field]: value }));
  const canPlaceOrder = form.name && form.phone && form.address && form.district && agreed && !isSubmitting;

  const handlePlaceOrder = async () => {
    if (!canPlaceOrder) return;
    
    setIsSubmitting(true);
    
    try {
      // Submit order to backend
      const response = await axios.post('/api/checkout/store', {
        customer_name: form.name,
        customer_email: form.email || 'no-email@example.com',
        customer_phone: form.phone,
        customer_address: form.address,
        district: form.district,
        area: form.area || form.district,
        payment_method: payment,
        notes: form.notes,
        // Include local cart items if no server cart
        items: !hasServerCart ? localItems.map(item => ({
          product_id: item.product.id,
          name: item.product.name,
          price: item.product.price,
          quantity: item.quantity,
          weight: item.selectedWeight,
        })) : undefined,
      });
      
      if (response.data.success) {
        // Clear local cart
        clearCart();
        
        toast.success('Order placed successfully!');
        
        // Redirect to order confirmation
        router.visit(`/order-confirmation/${response.data.invoice_id}`);
      } else {
        toast.error(response.data.message || 'Failed to place order');
      }
    } catch (error: any) {
      console.error('Order error:', error);
      toast.error(error.response?.data?.message || 'Failed to place order. Please try again.');
    } finally {
      setIsSubmitting(false);
    }
  };

  if (items.length === 0) {
    return (
      <MainLayout>
        <Head title="Checkout - Khadyobitan" />
        <div className="section-padding text-center">
          <div className="container-custom max-w-md mx-auto">
            <h1 className="font-heading text-2xl font-bold mb-2">{t("checkout.noItems")}</h1>
            <p className="font-body text-sm text-muted-foreground mb-6">{t("checkout.noItemsDesc")}</p>
            <Link href="/shop" className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity">{t("checkout.goToShop")}</Link>
          </div>
        </div>
      </MainLayout>
    );
  }

  return (
    <MainLayout>
      <Head title="Checkout - Khadyobitan" />
      <div className="section-padding">
      <div className="container-custom max-w-5xl">
        <nav className="flex items-center gap-2 text-sm font-body text-muted-foreground mb-8">
          <Link href="/" className="hover:text-primary">{t("common.home")}</Link>
          <ChevronRight className="h-3 w-3" />
          <Link href="/cart" className="hover:text-primary">{t("nav.cart")}</Link>
          <ChevronRight className="h-3 w-3" />
          <span className="text-foreground">{t("checkout.title")}</span>
        </nav>

        <h1 className="font-heading text-2xl sm:text-3xl font-bold mb-8">{t("checkout.title")}</h1>

        <div className="flex flex-col lg:flex-row gap-8">
          <div className="flex-1 space-y-8">
            <section className="bg-card border border-border rounded-xl p-5 sm:p-6">
              <h2 className="font-heading text-lg font-bold mb-5">{t("checkout.shippingInfo")}</h2>
              <div className="space-y-4">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block font-body text-sm font-medium mb-1">{t("checkout.fullName")} *</label>
                    <input value={form.name} onChange={(e) => updateField("name", e.target.value)} className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder={t("checkout.yourFullName")} />
                  </div>
                  <div>
                    <label className="block font-body text-sm font-medium mb-1">{t("checkout.phoneNumber")} *</label>
                    <input value={form.phone} onChange={(e) => updateField("phone", e.target.value)} className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder="01XXXXXXXXX" />
                  </div>
                </div>
                <div>
                  <label className="block font-body text-sm font-medium mb-1">{t("checkout.email")}</label>
                  <input value={form.email} onChange={(e) => updateField("email", e.target.value)} type="email" className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder="email@example.com" />
                </div>
                <div>
                  <label className="block font-body text-sm font-medium mb-1">{t("checkout.fullAddress")} *</label>
                  <input value={form.address} onChange={(e) => updateField("address", e.target.value)} className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder={t("checkout.houseRoadBlock")} />
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block font-body text-sm font-medium mb-1">{t("checkout.districtCity")} *</label>
                    <select value={form.district} onChange={(e) => updateField("district", e.target.value)} className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                      <option value="">{t("checkout.select")}</option>
                      {districtsList.map((d) => <option key={d} value={d}>{d}</option>)}
                    </select>
                  </div>
                </div>
                <div>
                  <label className="block font-body text-sm font-medium mb-1">{t("checkout.deliveryInstructions")}</label>
                  <textarea value={form.notes} onChange={(e) => updateField("notes", e.target.value)} rows={2} className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none" placeholder={t("checkout.anySpecialInstructions")} />
                </div>
              </div>
            </section>

            <section className="bg-card border border-border rounded-xl p-5 sm:p-6">
              <h2 className="font-heading text-lg font-bold mb-5">{t("checkout.paymentMethod")}</h2>
              <div className="space-y-3">
                {paymentMethods.map((pm) => (
                  <label key={pm.id} className={`flex items-center gap-4 p-4 rounded-xl border cursor-pointer transition-colors ${payment === pm.id ? "border-primary bg-primary/5" : "border-border hover:border-primary/50"}`}>
                    <input type="radio" name="payment" value={pm.id} checked={payment === pm.id} onChange={() => setPayment(pm.id)} className="accent-[hsl(var(--primary))]" />
                    <pm.icon className="h-5 w-5 text-primary shrink-0" />
                    <div>
                      <p className="font-body text-sm font-medium">{pm.label}</p>
                      <p className="font-body text-xs text-muted-foreground">{pm.desc}</p>
                    </div>
                  </label>
                ))}
              </div>
            </section>

            <section className="bg-card border border-border rounded-xl p-5 sm:p-6">
              <label className="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" checked={agreed} onChange={(e) => setAgreed(e.target.checked)} className="mt-0.5 accent-[hsl(var(--primary))]" />
                <span className="font-body text-sm text-muted-foreground">
                  {t("checkout.agreeTerms")} <Link href="/terms" className="text-primary underline">{t("checkout.termsConditions")}</Link> {t("checkout.and")} <Link href="/privacy" className="text-primary underline">{t("checkout.privacyPolicy")}</Link>.
                </span>
              </label>
              <button disabled={!canPlaceOrder} onClick={handlePlaceOrder} className="mt-5 w-full py-4 bg-primary text-primary-foreground rounded-lg font-body font-semibold text-base flex items-center justify-center gap-2 hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed">
                <ShieldCheck className="h-5 w-5" /> {isSubmitting ? 'Processing...' : `${t("checkout.placeOrder")} — ৳${total}`}
              </button>
            </section>
          </div>

          <div className="lg:w-80 shrink-0">
            <div className="bg-card border border-border rounded-xl p-6 sticky top-28">
              <h3 className="font-heading text-lg font-semibold mb-4">{t("cart.orderSummary")}</h3>
              <div className="space-y-2 mb-4 max-h-48 overflow-y-auto">
                {items.map((item: any, index: number) => (
                  <div key={item.product?.id || index} className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-muted rounded overflow-hidden shrink-0">
                      <img src={getProductImageSrc(item.product)} alt={item.product?.name} className="w-full h-full object-cover" />
                    </div>
                    <div className="flex-1 min-w-0">
                      <p className="font-body text-xs font-medium line-clamp-1">{item.product?.name}</p>
                      <p className="font-body text-[11px] text-muted-foreground">{item.selectedWeight} × {item.quantity}</p>
                    </div>
                    <span className="font-body text-xs font-semibold">৳{(item.product?.price || 0) * item.quantity}</span>
                  </div>
                ))}
              </div>
              <div className="space-y-2 font-body text-sm border-t border-border pt-4">
                <div className="flex justify-between"><span className="text-muted-foreground">{t("cart.subtotal")}</span><span>৳{sub}</span></div>
                <div className="flex justify-between">
                  <span className="text-muted-foreground">{t("cart.shipping")}</span>
                  <span className={shippingCost === 0 ? "text-primary font-medium" : ""}>{shippingCost === 0 ? t("cart.free") : `৳${shippingCost}`}</span>
                </div>
                <div className="border-t border-border pt-2 flex justify-between font-heading text-lg font-bold">
                  <span>{t("cart.total")}</span><span>৳{total}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </MainLayout>
  );
};
export default Checkout;


import React from 'react';
import { Link, Head, router } from "@inertiajs/react";
import { CheckCircle, Package, Printer, Download, Home, ShoppingBag } from "lucide-react";
import { getProductImageSrc } from "@/utils/imageUtils";
import { useRef, useEffect } from "react";
import { useTranslation } from "react-i18next";
import MainLayout from "@/Components/layout/MainLayout";
// import jsPDF from "jspdf";

interface OrderData {
  orderId: string;
  items: Array<{
    name: string;
    slug: string;
    price: number;
    quantity: number;
    weight: string;
  }>;
  form: {
    name: string;
    phone: string;
    email: string;
    address: string;
    district: string;
    notes: string;
  };
  payment: string;
  subtotal: number;
  shipping: number;
  total: number;
  date: string;
}

const OrderConfirmation = ({ order }: { order: OrderData | null }) => {
  const { t } = useTranslation();
  const invoiceRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!order) {
      router.visit("/", { replace: true });
    }
  }, [order]);

  const paymentLabels: Record<string, string> = {
    cod: t("checkout.cod"),
    bkash: t("checkout.bkash"),
    nagad: t("checkout.nagad"),
    card: t("checkout.card"),
    bank: t("checkout.bank"),
  };

  if (!order) return null;

  const handlePrint = () => window.print();

  const handleDownloadPDF = () => {
    alert("PDF Download is currently disabled.");
    /*
    const doc = new jsPDF();
    ...
    doc.save(`Invoice-${order.orderId}.pdf`);
    */
  };

  return (
    <MainLayout>
      <Head title="Order Confirmation - Khadyobitan" />
      <div className="section-padding">
        <div className="container-custom max-w-3xl">
          <div className="text-center mb-8">
            <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 mb-4">
              <CheckCircle className="h-8 w-8 text-primary" />
            </div>
            <h1 className="font-heading text-2xl sm:text-3xl font-bold mb-2">{t("orderConfirmation.successTitle")}</h1>
            <p className="font-body text-sm text-muted-foreground">{t("orderConfirmation.successDesc")}</p>
          </div>

          <div ref={invoiceRef} className="bg-card border border-border rounded-xl overflow-hidden print:shadow-none print:border-0">
            <div className="bg-primary/5 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-border">
              <div>
                <p className="font-heading text-lg font-bold">{t("orderConfirmation.invoice")}</p>
                <p className="font-body text-xs text-muted-foreground">{t("orderConfirmation.order")} #{order.orderId}</p>
              </div>
              <div className="text-left sm:text-right">
                <p className="font-body text-xs text-muted-foreground">{order.date}</p>
                <p className="font-body text-xs font-medium text-primary">{paymentLabels[order.payment]}</p>
              </div>
            </div>

            <div className="px-6 py-4 border-b border-border">
              <p className="font-body text-xs text-muted-foreground mb-1">{t("orderConfirmation.shipTo")}</p>
              <p className="font-body text-sm font-semibold">{order.form.name}</p>
              <p className="font-body text-xs text-muted-foreground">{order.form.phone}</p>
              <p className="font-body text-xs text-muted-foreground">{order.form.address}, {order.form.district}</p>
              {order.form.email && <p className="font-body text-xs text-muted-foreground">{order.form.email}</p>}
              {order.form.notes && <p className="font-body text-xs text-muted-foreground mt-1 italic">{t("orderConfirmation.note")}: {order.form.notes}</p>}
            </div>

            <div className="px-6 py-4 border-b border-border">
              <p className="font-body text-xs text-muted-foreground mb-3">{t("orderConfirmation.itemsOrdered")}</p>
              <div className="space-y-3">
                {order.items.map((item, i) => (
                  <div key={i} className="flex items-center gap-3">
                    <div className="w-12 h-12 bg-muted rounded-lg overflow-hidden shrink-0">
                      <img src={getProductImageSrc({ slug: item.slug, image: item.image })} alt={item.name} className="w-full h-full object-cover" />
                    </div>
                    <div className="flex-1 min-w-0">
                      <p className="font-body text-sm font-medium line-clamp-1">{item.name}</p>
                      <p className="font-body text-xs text-muted-foreground">{item.weight} × {item.quantity}</p>
                    </div>
                    <span className="font-body text-sm font-semibold shrink-0">৳{item.price * item.quantity}</span>
                  </div>
                ))}
              </div>
            </div>

            <div className="px-6 py-4 space-y-2 font-body text-sm">
              <div className="flex justify-between">
                <span className="text-muted-foreground">{t("cart.subtotal")}</span>
                <span>৳{order.subtotal}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-muted-foreground">{t("cart.shipping")}</span>
                <span className={order.shipping === 0 ? "text-primary font-medium" : ""}>{order.shipping === 0 ? t("cart.free") : `৳${order.shipping}`}</span>
              </div>
              <div className="border-t border-border pt-2 flex justify-between font-heading text-lg font-bold">
                <span>{t("cart.total")}</span>
                <span>৳{order.total}</span>
              </div>
            </div>
          </div>

          <div className="mt-6 flex flex-col sm:flex-row gap-3 print:hidden">
            <button onClick={handleDownloadPDF} className="flex-1 flex items-center justify-center gap-2 px-5 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity">
              <Download className="h-4 w-4" /> {t("orderConfirmation.downloadInvoice")}
            </button>
            <button onClick={handlePrint} className="flex-1 flex items-center justify-center gap-2 px-5 py-3 border border-border rounded-lg font-body font-medium text-sm hover:bg-muted transition-colors">
              <Printer className="h-4 w-4" /> {t("orderConfirmation.printInvoice")}
            </button>
            <Link href="/track-order" className="flex-1 flex items-center justify-center gap-2 px-5 py-3 border border-border rounded-lg font-body font-medium text-sm hover:bg-muted transition-colors">
              <Package className="h-4 w-4" /> {t("orderConfirmation.trackOrder")}
            </Link>
          </div>

          <div className="mt-4 flex flex-col sm:flex-row gap-3 print:hidden">
            <Link href="/shop" className="flex-1 flex items-center justify-center gap-2 px-5 py-3 border border-border rounded-lg font-body font-medium text-sm hover:bg-muted transition-colors">
              <ShoppingBag className="h-4 w-4" /> {t("orderConfirmation.continueShopping")}
            </Link>
            <Link href="/" className="flex-1 flex items-center justify-center gap-2 px-5 py-3 border border-border rounded-lg font-body font-medium text-sm hover:bg-muted transition-colors">
              <Home className="h-4 w-4" /> {t("orderConfirmation.goHome")}
            </Link>
          </div>
        </div>
      </div>
    </MainLayout>
  );
};
export default OrderConfirmation;


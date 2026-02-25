import { useState } from "react";
import { Package, Search, Truck, CheckCircle2, Clock, MapPin } from "lucide-react";
import { Button } from "@/components/ui/button";
import { motion } from "framer-motion";
import { useTranslation } from "react-i18next";

const mockOrders: Record<string, { status: string; steps: { label: string; date: string; done: boolean }[] }> = {
  "NP-10001": {
    status: "In Transit",
    steps: [
      { label: "Order Placed", date: "Feb 18, 2026", done: true },
      { label: "Processing", date: "Feb 18, 2026", done: true },
      { label: "Shipped", date: "Feb 19, 2026", done: true },
      { label: "In Transit", date: "Feb 20, 2026", done: true },
      { label: "Delivered", date: "Expected Feb 22", done: false },
    ],
  },
  "NP-10002": {
    status: "Delivered",
    steps: [
      { label: "Order Placed", date: "Feb 10, 2026", done: true },
      { label: "Processing", date: "Feb 10, 2026", done: true },
      { label: "Shipped", date: "Feb 11, 2026", done: true },
      { label: "In Transit", date: "Feb 12, 2026", done: true },
      { label: "Delivered", date: "Feb 14, 2026", done: true },
    ],
  },
};

const stepIcons = [Package, Clock, Truck, MapPin, CheckCircle2];

const OrderTracking = () => {
  const { t } = useTranslation();
  const [orderId, setOrderId] = useState("");
  const [searched, setSearched] = useState(false);
  const order = mockOrders[orderId.trim().toUpperCase()];

  const handleSearch = (e: React.FormEvent) => { e.preventDefault(); setSearched(true); };

  return (
    <>
      <Head title="Track Order - Khadyobitan" />
      <div className="min-h-[70vh] bg-muted/30">
      <div className="bg-primary/5 py-12 lg:py-16">
        <div className="container-custom text-center">
          <Truck className="h-10 w-10 text-primary mx-auto mb-4" />
          <h1 className="font-heading text-2xl lg:text-3xl font-bold text-foreground mb-2">{t("orderTracking.title")}</h1>
          <p className="font-body text-muted-foreground text-sm lg:text-base max-w-md mx-auto">{t("orderTracking.desc")}</p>
        </div>
      </div>

      <div className="container-custom py-10 max-w-xl mx-auto">
        <form onSubmit={handleSearch} className="flex gap-2 mb-8">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <input type="text" value={orderId} onChange={(e) => { setOrderId(e.target.value); setSearched(false); }} placeholder={t("orderTracking.placeholder")} className="w-full pl-10 pr-4 py-3 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/30 font-body text-sm" />
          </div>
          <Button type="submit" className="px-6">{t("orderTracking.track")}</Button>
        </form>

        {searched && !order && (
          <div className="text-center py-12">
            <Package className="h-12 w-12 text-muted-foreground/40 mx-auto mb-3" />
            <p className="font-body text-muted-foreground">{t("orderTracking.noOrderFound")} "<span className="font-semibold text-foreground">{orderId}</span>"</p>
            <p className="font-body text-xs text-muted-foreground mt-1">{t("orderTracking.tryDemo")}</p>
          </div>
        )}

        {searched && order && (
          <motion.div initial={{ opacity: 0, y: 16 }} animate={{ opacity: 1, y: 0 }} className="bg-background rounded-xl border border-border p-6">
            <div className="flex items-center justify-between mb-6">
              <div>
                <p className="font-body text-xs text-muted-foreground">{t("orderTracking.orderId")}</p>
                <p className="font-heading font-bold text-foreground">{orderId.trim().toUpperCase()}</p>
              </div>
              <span className={`px-3 py-1 rounded-full text-xs font-semibold font-body ${order.status === "Delivered" ? "bg-primary/10 text-primary" : "bg-accent/20 text-accent-foreground"}`}>
                {order.status}
              </span>
            </div>

            <div className="space-y-0">
              {order.steps.map((step, i) => {
                const Icon = stepIcons[i];
                const isLast = i === order.steps.length - 1;
                return (
    <>
      <Head title="Track Order - Khadyobitan" />
      <div key={step.label} className="flex gap-4">
                    <div className="flex flex-col items-center">
                      <div className={`h-8 w-8 rounded-full flex items-center justify-center shrink-0 ${step.done ? "bg-primary text-primary-foreground" : "bg-muted text-muted-foreground"}`}>
                        <Icon className="h-4 w-4" />
                      </div>
                      {!isLast && <div className={`w-0.5 h-8 ${step.done ? "bg-primary" : "bg-border"}`} />}
                    </div>
                    <div className="pt-1 pb-4">
                      <p className={`font-body text-sm font-medium ${step.done ? "text-foreground" : "text-muted-foreground"}`}>{step.label}</p>
                      <p className="font-body text-xs text-muted-foreground">{step.date}</p>
                    </div>
                  </div>
                );
              })}
            </div>
          </motion.div>
        )}
      </div>
    </div>
    </>
  );
};
export default OrderTracking;


import { motion } from "framer-motion";
import { Truck, Clock, MapPin, Package } from "lucide-react";
import { useTranslation } from "react-i18next";

const ShippingPolicy = () => {
  const { t } = useTranslation();

  const deliveryZones = [
    { area: "Dhaka City", time: "1-2 business days", cost: "৳60 (Free above ৳1000)" },
    { area: "Dhaka Suburbs", time: "2-3 business days", cost: "৳80 (Free above ৳1500)" },
    { area: "Chittagong, Sylhet, Rajshahi", time: "3-5 business days", cost: "৳100 (Free above ৳2000)" },
    { area: "Other Districts", time: "5-7 business days", cost: "৳120 (Free above ৳2500)" },
  ];

  const highlights = [
    { icon: Truck, label: t("shippingPolicy.freeShipping"), sub: t("shippingPolicy.freeShippingDesc") },
    { icon: Clock, label: t("shippingPolicy.fastDelivery"), sub: t("shippingPolicy.fastDeliveryDesc") },
    { icon: Package, label: t("shippingPolicy.securePacking"), sub: t("shippingPolicy.securePackingDesc") },
    { icon: MapPin, label: t("shippingPolicy.nationwide"), sub: t("shippingPolicy.nationwideDesc") },
  ];

  return (
    <div>
      <section className="bg-gradient-earthy text-primary-foreground section-padding">
        <div className="container-custom text-center">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <h1 className="font-heading text-4xl font-bold mb-3">{t("shippingPolicy.title")}</h1>
            <p className="font-body text-base opacity-80 max-w-lg mx-auto">{t("shippingPolicy.desc")}</p>
          </motion.div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-custom max-w-3xl">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            {highlights.map((item) => (
              <div key={item.label} className="text-center bg-card border border-border rounded-xl p-4">
                <item.icon className="h-6 w-6 text-primary mx-auto mb-2" />
                <p className="font-heading text-sm font-semibold text-foreground">{item.label}</p>
                <p className="font-body text-xs text-muted-foreground">{item.sub}</p>
              </div>
            ))}
          </div>

          <h2 className="font-heading text-2xl font-bold text-foreground mb-4">{t("shippingPolicy.deliveryZones")}</h2>
          <div className="border border-border rounded-xl overflow-hidden mb-8">
            <table className="w-full">
              <thead className="bg-muted">
                <tr>
                  <th className="text-left px-4 py-3 font-body text-sm font-semibold text-foreground">{t("shippingPolicy.area")}</th>
                  <th className="text-left px-4 py-3 font-body text-sm font-semibold text-foreground">{t("shippingPolicy.deliveryTime")}</th>
                  <th className="text-left px-4 py-3 font-body text-sm font-semibold text-foreground">{t("shippingPolicy.shippingCost")}</th>
                </tr>
              </thead>
              <tbody>
                {deliveryZones.map((zone) => (
                  <tr key={zone.area} className="border-t border-border">
                    <td className="px-4 py-3 font-body text-sm text-foreground">{zone.area}</td>
                    <td className="px-4 py-3 font-body text-sm text-muted-foreground">{zone.time}</td>
                    <td className="px-4 py-3 font-body text-sm text-muted-foreground">{zone.cost}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          <div className="prose-sm space-y-6 font-body text-sm text-muted-foreground leading-relaxed">
            <div>
              <h3 className="font-heading text-lg font-semibold text-foreground mb-2">{t("shippingPolicy.orderProcessing")}</h3>
              <p>{t("shippingPolicy.orderProcessingDesc")}</p>
            </div>
            <div>
              <h3 className="font-heading text-lg font-semibold text-foreground mb-2">{t("shippingPolicy.packaging")}</h3>
              <p>{t("shippingPolicy.packagingDesc")}</p>
            </div>
            <div>
              <h3 className="font-heading text-lg font-semibold text-foreground mb-2">{t("shippingPolicy.deliveryAttempts")}</h3>
              <p>{t("shippingPolicy.deliveryAttemptsDesc")}</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default ShippingPolicy;

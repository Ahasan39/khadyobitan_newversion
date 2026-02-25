import { motion } from "framer-motion";
import { RotateCcw, Shield, Clock, CheckCircle } from "lucide-react";
import { useTranslation } from "react-i18next";

const ReturnPolicy = () => {
  const { t } = useTranslation();

  const highlights = [
    { icon: RotateCcw, label: t("returnPolicy.sevenDayReturns"), sub: t("returnPolicy.sevenDayReturnsDesc") },
    { icon: Shield, label: t("returnPolicy.qualityGuarantee"), sub: t("returnPolicy.qualityGuaranteeDesc") },
    { icon: Clock, label: t("returnPolicy.fastRefunds"), sub: t("returnPolicy.fastRefundsDesc") },
    { icon: CheckCircle, label: t("returnPolicy.easyProcess"), sub: t("returnPolicy.easyProcessDesc") },
  ];

  return (
    <>
      <Head title="Return Policy - Khadyobitan" />
      <div>
      <section className="bg-gradient-earthy text-primary-foreground section-padding">
        <div className="container-custom text-center">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <h1 className="font-heading text-4xl font-bold mb-3">{t("returnPolicy.title")}</h1>
            <p className="font-body text-base opacity-80 max-w-lg mx-auto">{t("returnPolicy.desc")}</p>
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

          <div className="space-y-8 font-body text-sm text-muted-foreground leading-relaxed">
            <div>
              <h2 className="font-heading text-xl font-bold text-foreground mb-3">{t("returnPolicy.returnEligibility")}</h2>
              <ul className="list-disc list-inside space-y-2">
                <li>Products can be returned within <strong className="text-foreground">7 days</strong> of delivery.</li>
                <li>Items must be unused, unopened, and in original packaging.</li>
                <li>Perishable items (honey, oils, dates) can only be returned if damaged or defective on arrival.</li>
                <li>Photo/video evidence is required for damage claims.</li>
              </ul>
            </div>

            <div>
              <h2 className="font-heading text-xl font-bold text-foreground mb-3">{t("returnPolicy.howToRequestReturn")}</h2>
              <ol className="list-decimal list-inside space-y-2">
                <li>Contact us at <strong className="text-foreground">support@khadyobitan.com</strong> or call <strong className="text-foreground">+880 1700-000000</strong>.</li>
                <li>Provide your order number and reason for return.</li>
                <li>For damaged items, attach clear photos of the product and packaging.</li>
                <li>Our team will approve and arrange pickup within 24 hours.</li>
              </ol>
            </div>

            <div>
              <h2 className="font-heading text-xl font-bold text-foreground mb-3">{t("returnPolicy.refundProcess")}</h2>
              <p>Once we receive and inspect the returned item, your refund will be processed within <strong className="text-foreground">3-5 business days</strong>. Refunds are issued to the original payment method.</p>
            </div>

            <div>
              <h2 className="font-heading text-xl font-bold text-foreground mb-3">{t("returnPolicy.nonReturnable")}</h2>
              <ul className="list-disc list-inside space-y-2">
                <li>Opened or used food products (unless defective).</li>
                <li>Gift cards and promotional items.</li>
                <li>Products with tampered packaging.</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
    </div>
    </>
  );
};
export default ReturnPolicy;


import { motion } from "framer-motion";
import { useTranslation } from "react-i18next";

const Terms = () => {
  const { t } = useTranslation();

  return (
    <>
      <Head title="Terms & Conditions - Khadyobitan" />
      <div>
      <section className="bg-gradient-earthy text-primary-foreground section-padding">
        <div className="container-custom text-center">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <h1 className="font-heading text-4xl font-bold mb-3">{t("terms.title")}</h1>
            <p className="font-body text-base opacity-80">{t("terms.lastUpdated")}</p>
          </motion.div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-custom max-w-3xl space-y-8 font-body text-sm text-muted-foreground leading-relaxed">
          {[
            { title: "1. General", text: "By accessing and using Khadyobitan (khadyobitan.com), you agree to be bound by these Terms and Conditions. If you do not agree, please refrain from using our website." },
            { title: "2. Products & Pricing", text: "All product descriptions, images, and prices on our website are as accurate as possible. We reserve the right to modify prices and availability without prior notice. Prices are listed in Bangladeshi Taka (৳)." },
            { title: "3. Orders & Payment", text: "By placing an order, you agree to provide accurate and complete information. We accept Cash on Delivery (COD), bKash, Nagad, and credit/debit card payments. Orders are subject to availability and acceptance." },
            { title: "4. Delivery", text: "We deliver across Bangladesh. Delivery times are estimates and may vary depending on location and conditions. We are not liable for delays caused by courier services or force majeure events." },
            { title: "5. Returns & Refunds", text: "Returns are accepted within 7 days of delivery per our Return Policy. Refunds are processed within 3-5 business days after inspection of returned items." },
            { title: "6. User Accounts", text: "You are responsible for maintaining the confidentiality of your account credentials. You agree to notify us immediately of any unauthorized access to your account." },
            { title: "7. Intellectual Property", text: "All content on this website — including text, images, logos, and design — is the property of Khadyobitan and is protected by intellectual property laws. Unauthorized use is prohibited." },
            { title: "8. Limitation of Liability", text: "Khadyobitan shall not be liable for any indirect, incidental, or consequential damages arising from the use of our products or website. Our total liability is limited to the order value." },
            { title: "9. Contact", text: "For questions about these Terms, contact us at legal@khadyobitan.com or call +880 1700-000000." },
          ].map((s) => (
            <div key={s.title}>
              <h2 className="font-heading text-xl font-bold text-foreground mb-2">{s.title}</h2>
              <p>{s.text}</p>
            </div>
          ))}
        </div>
      </section>
    </div>
    </>
  );
};
export default Terms;


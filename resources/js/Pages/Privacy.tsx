import { motion } from "framer-motion";
import { useTranslation } from "react-i18next";

const Privacy = () => {
  const { t } = useTranslation();

  return (
    <>
      <Head title="Privacy Policy - Khadyobitan" />
      <div>
      <section className="bg-gradient-earthy text-primary-foreground section-padding">
        <div className="container-custom text-center">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <h1 className="font-heading text-4xl font-bold mb-3">{t("privacy.title")}</h1>
            <p className="font-body text-base opacity-80">{t("privacy.lastUpdated")}</p>
          </motion.div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-custom max-w-3xl space-y-8 font-body text-sm text-muted-foreground leading-relaxed">
          {[
            { title: "Information We Collect", text: "We collect personal information you provide when placing orders, creating accounts, or contacting us. This includes your name, email, phone number, delivery address, and payment information." },
            { title: "How We Use Your Information", text: "Your information is used to process orders, improve our services, send relevant updates and promotions (with your consent), and provide customer support. We never sell your data to third parties." },
            { title: "Data Security", text: "We implement industry-standard security measures to protect your personal data. Payment information is processed through secure, encrypted channels. We do not store credit/debit card numbers on our servers." },
            { title: "Cookies", text: "Our website uses cookies to enhance your browsing experience, remember your preferences, and analyze site traffic. You can manage cookie preferences through your browser settings." },
            { title: "Third-Party Services", text: "We use trusted third-party services for payment processing (bKash, Nagad, SSL Commerz) and delivery. These partners have their own privacy policies governing data handling." },
            { title: "Your Rights", text: "You have the right to access, correct, or delete your personal data. You can unsubscribe from marketing communications at any time. Contact us at privacy@khadyobitan.com for any data-related requests." },
            { title: "Data Retention", text: "We retain your personal data for as long as your account is active or as needed to provide services. Order records are kept for 3 years for legal and accounting purposes." },
            { title: "Changes to This Policy", text: "We may update this Privacy Policy from time to time. We will notify registered users of any significant changes via email." },
            { title: "Contact Us", text: "For privacy concerns or questions, contact our Data Protection Officer at privacy@khadyobitan.com or call +880 1700-000000." },
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
export default Privacy;


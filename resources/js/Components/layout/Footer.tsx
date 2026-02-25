import { Link } from "react-router-dom";
import { Facebook, Instagram, Youtube, Mail } from "lucide-react";
import { motion } from "framer-motion";
import { useTranslation } from "react-i18next";
import logo from "@/assets/logo.jpeg";
import paymentMethods from "@/assets/we-accept-payment.png";

const Footer = () => {
  const { t } = useTranslation();

  return (
    <footer className="bg-foreground text-primary-foreground">
      <div className="container-custom py-16">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10"
        >
          <div className="lg:col-span-2">
            <Link to="/" className="flex items-center gap-2 mb-4 group">
              <div className="bg-white/90 rounded-lg p-1.5">
                <img src={logo} alt="Khadyobitan logo" className="h-8 w-auto object-contain" />
              </div>
            </Link>
            <p className="text-sm opacity-70 leading-relaxed font-body mb-6">{t("footer.tagline")}</p>
            <div className="flex gap-3">
              {[Facebook, Instagram, Youtube].map((Icon, i) => (
                <a key={i} href="#" className="p-2.5 rounded-xl bg-primary-foreground/10 hover:bg-accent/20 hover:text-accent transition-all duration-300">
                  <Icon className="h-4 w-4" />
                </a>
              ))}
            </div>
          </div>

          <div className="grid grid-cols-2 gap-6 lg:contents">
            <div>
              <h4 className="font-heading text-base font-semibold mb-4">{t("footer.quickLinks")}</h4>
              <ul className="space-y-2.5 font-body text-sm opacity-70">
                {[
                  { label: t("footer.aboutUs"), path: "/about" },
                  { label: t("nav.shop"), path: "/shop" },
                  { label: t("footer.orderTrack"), path: "/track-order" },
                  { label: t("footer.blogRecipes"), path: "/blog" },
                  { label: "FAQ", path: "/faq" },
                ].map((l) => (
                  <li key={l.path}>
                    <Link to={l.path} className="hover:opacity-100 hover:text-accent hover:translate-x-1 transition-all duration-200 inline-block">{l.label}</Link>
                  </li>
                ))}
              </ul>
            </div>

            <div>
              <h4 className="font-heading text-base font-semibold mb-4">{t("footer.customerService")}</h4>
              <ul className="space-y-2.5 font-body text-sm opacity-70">
                {[
                  { label: t("footer.shippingDelivery"), path: "/shipping" },
                  { label: t("footer.returnsRefunds"), path: "/returns" },
                  { label: t("footer.termsConditions"), path: "/terms" },
                  { label: t("footer.privacyPolicy"), path: "/privacy" },
                  { label: t("nav.contact"), path: "/contact" },
                  { label: t("footer.myAccount"), path: "/account" },
                ].map((l) => (
                  <li key={l.path}>
                    <Link to={l.path} className="hover:opacity-100 hover:text-accent hover:translate-x-1 transition-all duration-200 inline-block">{l.label}</Link>
                  </li>
                ))}
              </ul>
            </div>
          </div>

          <div>
            <h4 className="font-heading text-base font-semibold mb-4">{t("footer.get10Off")}</h4>
            <p className="text-sm opacity-70 mb-4 font-body">{t("footer.subscribeDesc")}</p>
            <div className="flex">
              <input type="email" placeholder={t("footer.yourEmail")} className="flex-1 px-3 py-2.5 rounded-l-xl bg-primary-foreground/10 border-0 text-primary-foreground placeholder:text-primary-foreground/40 text-sm focus:outline-none focus:ring-2 focus:ring-accent/30 font-body" />
              <button className="px-4 py-2.5 rounded-r-xl bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors font-body">
                <Mail className="h-4 w-4" />
              </button>
            </div>
          </div>
        </motion.div>

        <div className="border-t border-primary-foreground/10 mt-12 pt-6 flex justify-center">
          <img src={paymentMethods} alt="We accept City Touch, DBBL Nexus, American Express, bKash, Rocket" className="h-16 sm:h-20 w-auto object-contain" />
        </div>

        <div className="border-t border-primary-foreground/10 mt-6 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs opacity-50 font-body">
          <p>{t("footer.copyright")}</p>
          <div className="flex gap-4">
            <Link to="/terms" className="hover:opacity-100 hover:text-accent transition-all duration-200">{t("footer.terms")}</Link>
            <Link to="/privacy" className="hover:opacity-100 hover:text-accent transition-all duration-200">{t("footer.privacy")}</Link>
            <Link to="/shipping" className="hover:opacity-100 hover:text-accent transition-all duration-200">{t("shippingPolicy.title")}</Link>
          </div>
        </div>

        <div className="mt-4 pt-4 border-t border-primary-foreground/10 text-center">
          <p className="text-xs opacity-50 font-body">
            Design and Developed by{" "}
            <a 
              href="https://ahasan39.github.io/" 
              target="_blank" 
              rel="noopener noreferrer"
              className="hover:opacity-100 hover:text-accent transition-all duration-200 font-medium"
            >
              Ahasan39
            </a>
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;

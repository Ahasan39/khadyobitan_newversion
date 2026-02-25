import { useState, useEffect } from "react";
import { Link, useLocation } from "react-router-dom";
import { X, Gift, Copy, Check } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";
import { useTranslation } from "react-i18next";

const PromoPopup = () => {
  const { t } = useTranslation();
  const [show, setShow] = useState(false);
  const [copied, setCopied] = useState(false);
  const location = useLocation();

  useEffect(() => {
    if (location.pathname !== "/") return;
    if (sessionStorage.getItem("promo-dismissed") === "true") return;
    const timer = setTimeout(() => setShow(true), 10000);
    return () => clearTimeout(timer);
  }, [location.pathname]);

  const dismiss = () => {
    setShow(false);
    sessionStorage.setItem("promo-dismissed", "true");
  };

  const copyCode = () => {
    navigator.clipboard.writeText("WELCOME10");
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <AnimatePresence>
      {show && (
        <>
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={dismiss}
            className="fixed inset-0 bg-foreground/50 backdrop-blur-sm z-[60]"
          />

          <motion.div
            initial={{ opacity: 0, scale: 0.9, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.9, y: 20 }}
            transition={{ type: "spring", stiffness: 300, damping: 25 }}
            className="fixed inset-0 z-[61] flex items-center justify-center p-4"
          >
            <div className="relative bg-card border border-border rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
              <button
                onClick={dismiss}
                className="absolute top-3 right-3 p-1.5 rounded-full bg-muted hover:bg-muted-foreground/20 transition-colors z-10"
                aria-label="Close promotion"
              >
                <X className="h-4 w-4 text-muted-foreground" />
              </button>

              <div className="bg-primary py-6 px-6 text-center">
                <motion.div
                  initial={{ scale: 0 }}
                  animate={{ scale: 1 }}
                  transition={{ delay: 0.2, type: "spring", stiffness: 200 }}
                  className="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary-foreground/20 mb-3"
                >
                  <Gift className="h-7 w-7 text-primary-foreground" />
                </motion.div>
                <h3 className="font-heading text-xl font-bold text-primary-foreground">
                  {t("promo.welcomeTitle")}
                </h3>
                <p className="font-body text-sm text-primary-foreground/80 mt-1">
                  {t("promo.welcomeDesc")}
                </p>
              </div>

              <div className="p-6 text-center">
                <p className="font-heading text-4xl font-bold text-primary mb-1">{t("promo.off")}</p>
                <p className="font-body text-sm text-muted-foreground mb-4">
                  {t("promo.useCodeBelow")}
                </p>

                <button
                  onClick={copyCode}
                  className="group flex items-center justify-center gap-2 w-full py-3 px-4 bg-muted border-2 border-dashed border-primary/30 rounded-xl hover:border-primary/60 transition-colors"
                >
                  <span className="font-heading text-lg font-bold tracking-widest text-foreground">
                    WELCOME10
                  </span>
                  {copied ? (
                    <Check className="h-4 w-4 text-primary" />
                  ) : (
                    <Copy className="h-4 w-4 text-muted-foreground group-hover:text-primary transition-colors" />
                  )}
                </button>
                <p className="font-body text-xs text-muted-foreground mt-2">
                  {copied ? t("promo.codeCopied") : t("promo.clickToCopy")}
                </p>

                <Link
                  to="/shop"
                  onClick={dismiss}
                  className="mt-4 inline-flex items-center justify-center w-full py-3 bg-primary text-primary-foreground rounded-xl font-body font-semibold text-sm hover:shadow-lg hover:shadow-primary/25 transition-all duration-300"
                >
                  {t("promo.shopNow")}
                </Link>

                <button
                  onClick={dismiss}
                  className="mt-2 font-body text-xs text-muted-foreground hover:text-foreground transition-colors"
                >
                  {t("promo.noThanks")}
                </button>
              </div>
            </div>
          </motion.div>
        </>
      )}
    </AnimatePresence>
  );
};

export default PromoPopup;

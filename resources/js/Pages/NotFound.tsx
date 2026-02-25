import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { Leaf, ArrowRight, Home, ShoppingCart } from "lucide-react";
import { useTranslation } from "react-i18next";

const NotFound = () => {
  const { t } = useTranslation();

  return (
    <div className="min-h-[70vh] flex items-center justify-center section-padding">
      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="text-center max-w-md">
        <Leaf className="h-16 w-16 text-primary mx-auto mb-6 opacity-30" />
        <h1 className="font-heading text-6xl font-bold text-foreground mb-2">{t("notFound.title")}</h1>
        <p className="font-heading text-xl text-foreground mb-2">{t("notFound.subtitle")}</p>
        <p className="font-body text-sm text-muted-foreground mb-8">{t("notFound.desc")}</p>
        <div className="flex flex-wrap justify-center gap-3">
          <Link to="/" className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity">
            <Home className="h-4 w-4" /> {t("notFound.goHome")}
          </Link>
          <Link to="/shop" className="inline-flex items-center gap-2 px-6 py-3 border border-border rounded-lg font-body font-medium text-sm text-foreground hover:bg-muted transition-colors">
            <ShoppingCart className="h-4 w-4" /> {t("notFound.browseShop")}
          </Link>
        </div>
      </motion.div>
    </div>
  );
};

export default NotFound;

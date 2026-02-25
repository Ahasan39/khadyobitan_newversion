import { Link, useLocation } from "react-router-dom";
import { Home, Search, ShoppingCart, Heart, User } from "lucide-react";
import { useCartStore } from "@/store/cartStore";
import { useTranslation } from "react-i18next";

const MobileBottomNav = () => {
  const { t } = useTranslation();
  const location = useLocation();
  const totalItems = useCartStore((s) => s.totalItems());
  const wishlist = useCartStore((s) => s.wishlist);

  const navItems = [
    { icon: Home, label: t("nav.home"), path: "/" },
    { icon: Search, label: t("nav.shop"), path: "/shop" },
    { icon: ShoppingCart, label: t("nav.cart"), path: "/cart", showBadge: "cart" as const },
    { icon: Heart, label: t("nav.wishlist"), path: "/wishlist", showBadge: "wishlist" as const },
    { icon: User, label: t("nav.account"), path: "/login" },
  ];

  return (
    <nav className="fixed bottom-0 left-0 right-0 z-50 bg-background/95 backdrop-blur-md border-t border-border lg:hidden">
      <div className="flex items-center justify-around h-16 px-2 pb-safe">
        {navItems.map((item) => {
          const isActive = location.pathname === item.path;
          const badgeCount = item.showBadge === "cart" ? totalItems : item.showBadge === "wishlist" ? wishlist.length : 0;
          return (
            <Link key={item.path} to={item.path} className={`flex flex-col items-center justify-center gap-0.5 flex-1 py-1.5 rounded-lg transition-colors ${isActive ? "text-primary" : "text-muted-foreground"}`}>
              <div className="relative">
                <item.icon className={`h-5 w-5 ${isActive ? "stroke-[2.5]" : ""}`} />
                {badgeCount > 0 && (
                  <span className="absolute -top-1.5 -right-2 bg-primary text-primary-foreground text-[9px] font-bold rounded-full h-3.5 w-3.5 flex items-center justify-center">{badgeCount}</span>
                )}
              </div>
              <span className={`text-[10px] font-body ${isActive ? "font-semibold" : "font-medium"}`}>{item.label}</span>
            </Link>
          );
        })}
      </div>
    </nav>
  );
};

export default MobileBottomNav;

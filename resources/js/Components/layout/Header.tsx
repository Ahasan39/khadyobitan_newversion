import { useState, useEffect, useRef } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { ShoppingCart, Heart, Search, Menu, X, User } from "lucide-react";
import { useCartStore } from "@/store/cartStore";
import { motion, AnimatePresence } from "framer-motion";
import { products } from "@/data/products";
import { useTranslation } from "react-i18next";
import LanguageSwitcher from "@/components/LanguageSwitcher";
import logo from "@/assets/logo.jpeg";

const Header = () => {
  const { t } = useTranslation();
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState("");
  const searchRef = useRef<HTMLInputElement>(null);
  const totalItems = useCartStore((s) => s.totalItems());
  const wishlist = useCartStore((s) => s.wishlist);
  const location = useLocation();
  const navigate = useNavigate();

  const navLinks = [
    { label: t("nav.home"), path: "/" },
    { label: t("nav.shop"), path: "/shop" },
    { label: t("nav.orderTrack"), path: "/track-order" },
    { label: t("nav.blog"), path: "/blog" },
    { label: t("nav.about"), path: "/about" },
    { label: t("nav.contact"), path: "/contact" },
  ];

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 40);
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    setMobileOpen(false);
    setSearchOpen(false);
    setSearchQuery("");
  }, [location]);

  useEffect(() => {
    if (searchOpen && searchRef.current) {
      searchRef.current.focus();
    }
  }, [searchOpen]);

  const filteredProducts = searchQuery.trim()
    ? products.filter((p) =>
        p.name.toLowerCase().includes(searchQuery.toLowerCase())
      ).slice(0, 5)
    : [];

  return (
    <>
      <header
        className={`relative lg:sticky lg:top-0 z-50 transition-all duration-300 border-b ${
          scrolled
            ? "bg-background/95 backdrop-blur-md shadow-md border-border/60"
            : "bg-background border-transparent"
        }`}
      >
        <div className="container-custom flex items-center justify-between h-14 lg:h-16">
          <Link to="/" className="flex items-center gap-2 group">
            <img src={logo} alt="Khadyobitan logo" className="h-8 lg:h-10 w-auto object-contain" />
          </Link>

          <nav className="hidden lg:flex items-center gap-1">
            {navLinks.map((link) => {
              const isActive = location.pathname === link.path;
              return (
                <Link
                  key={link.path}
                  to={link.path}
                  className={`relative px-3.5 py-2 rounded-full text-sm font-medium font-body tracking-wide transition-all duration-200 ${
                    isActive
                      ? "text-primary bg-primary/10"
                      : "text-foreground/75 hover:text-primary hover:bg-primary/5"
                  }`}
                >
                  {link.label}
                </Link>
              );
            })}
          </nav>

          <div className="flex items-center gap-0.5 sm:gap-1 lg:gap-1.5">
            <AnimatePresence mode="wait">
              {searchOpen ? (
                <motion.div
                  key="search-input"
                  initial={{ width: 0, opacity: 0 }}
                  animate={{ width: "auto", opacity: 1 }}
                  exit={{ width: 0, opacity: 0 }}
                  transition={{ duration: 0.25, ease: "easeInOut" }}
                  className="relative overflow-hidden"
                >
                  <div className="flex items-center bg-muted rounded-full pl-3 pr-1 h-9 min-w-[180px] sm:min-w-[220px] lg:min-w-[260px]">
                    <Search className="h-4 w-4 text-muted-foreground shrink-0" />
                    <input
                      ref={searchRef}
                      type="text"
                      value={searchQuery}
                      onChange={(e) => setSearchQuery(e.target.value)}
                      placeholder={t("search.placeholder")}
                      className="flex-1 bg-transparent border-0 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none ml-2 mr-1 font-body"
                      onKeyDown={(e) => {
                        if (e.key === "Escape") {
                          setSearchOpen(false);
                          setSearchQuery("");
                        }
                      }}
                    />
                    <button
                      onClick={() => { setSearchOpen(false); setSearchQuery(""); }}
                      className="p-1.5 rounded-full hover:bg-background/60 transition-colors"
                    >
                      <X className="h-3.5 w-3.5 text-muted-foreground" />
                    </button>
                  </div>

                  {searchQuery.trim() && (
                    <div className="absolute top-full mt-2 left-0 right-0 bg-card border border-border rounded-xl shadow-lg overflow-hidden z-50">
                      {filteredProducts.length > 0 ? (
                        filteredProducts.map((p) => (
                          <button
                            key={p.id}
                            onClick={() => {
                              navigate(`/product/${p.id}`);
                              setSearchOpen(false);
                              setSearchQuery("");
                            }}
                            className="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-muted/60 transition-colors text-left"
                          >
                            <span className="text-lg">{p.image}</span>
                            <div className="min-w-0">
                              <p className="text-sm font-medium text-foreground truncate">{p.name}</p>
                              <p className="text-xs text-muted-foreground">৳{p.price}</p>
                            </div>
                          </button>
                        ))
                      ) : (
                        <p className="px-4 py-3 text-sm text-muted-foreground text-center">{t("search.noResults")}</p>
                      )}
                    </div>
                  )}
                </motion.div>
              ) : (
                <motion.button
                  key="search-btn"
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 1 }}
                  exit={{ opacity: 0 }}
                  onClick={() => setSearchOpen(true)}
                  className="p-2 rounded-full hover:bg-muted transition-colors"
                  aria-label="Search"
                >
                  <Search className="h-5 w-5 text-foreground/75" />
                </motion.button>
              )}
            </AnimatePresence>

            <LanguageSwitcher />

            <Link to="/login" className="p-2 rounded-full hover:bg-muted transition-colors hidden lg:flex" aria-label={t("nav.account")}>
              <User className="h-5 w-5 text-foreground/75" />
            </Link>
            <Link to="/wishlist" className="p-2 rounded-full hover:bg-muted transition-colors relative hidden lg:flex" aria-label={t("nav.wishlist")}>
              <Heart className="h-5 w-5 text-foreground/75" />
              {wishlist.length > 0 && (
                <span className="absolute top-0.5 right-0.5 bg-accent text-accent-foreground text-[9px] font-bold rounded-full h-4 w-4 flex items-center justify-center ring-2 ring-background">
                  {wishlist.length}
                </span>
              )}
            </Link>
            <Link to="/cart" className="p-2 rounded-full hover:bg-muted transition-colors relative hidden lg:flex" aria-label={t("nav.cart")}>
              <ShoppingCart className="h-5 w-5 text-foreground/75" />
              {totalItems > 0 && (
                <span className="absolute top-0.5 right-0.5 bg-primary text-primary-foreground text-[9px] font-bold rounded-full h-4 w-4 flex items-center justify-center ring-2 ring-background">
                  {totalItems}
                </span>
              )}
            </Link>
            <button className="lg:hidden p-2 rounded-full hover:bg-muted transition-colors" onClick={() => setMobileOpen(!mobileOpen)} aria-label="Menu">
              {mobileOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
            </button>
          </div>
        </div>

        <AnimatePresence>
          {mobileOpen && (
            <motion.div
              initial={{ height: 0, opacity: 0 }}
              animate={{ height: "auto", opacity: 1 }}
              exit={{ height: 0, opacity: 0 }}
              className="lg:hidden overflow-hidden border-t border-border bg-background"
            >
              <nav className="container-custom py-4 flex flex-col gap-1">
                {navLinks.map((link) => {
                  const isActive = location.pathname === link.path;
                  return (
                    <Link
                      key={link.path}
                      to={link.path}
                      className={`py-2.5 px-3 rounded-lg font-body text-base font-medium transition-all ${
                        isActive
                          ? "text-primary bg-primary/10"
                          : "text-foreground hover:text-primary hover:bg-muted/60"
                      }`}
                    >
                      {link.label}
                    </Link>
                  );
                })}
              </nav>
            </motion.div>
          )}
        </AnimatePresence>
      </header>
    </>
  );
};

export default Header;

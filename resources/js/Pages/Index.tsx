import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { motion, AnimatePresence } from "framer-motion";
import { Leaf, Truck, Shield, Heart, Zap, Award, Star, ChevronRight, ArrowRight, Clock, BadgeCheck, Sparkles } from "lucide-react";
import { useTranslation } from "react-i18next";
import { blogPosts } from "@/data/blogPosts";
import ProductCard from "@/components/ProductCard";
import { products, categories, testimonials } from "@/data/products";
import heroSlide1 from "@/assets/hero-slide-1.jpg";
import heroSlide2 from "@/assets/hero-slide-2.jpg";
import heroSlide3 from "@/assets/hero-slide-3.jpg";
import catSalts from "@/assets/cat-salts.jpg";
import catSeeds from "@/assets/cat-seeds.jpg";
import catSweeteners from "@/assets/cat-sweeteners.jpg";
import catDates from "@/assets/cat-dates.jpg";
import catOils from "@/assets/cat-oils.jpg";
import catNuts from "@/assets/cat-nuts.jpg";

const categoryImages: Record<string, string> = {
  "cat-salts": catSalts,
  "cat-seeds": catSeeds,
  "cat-sweeteners": catSweeteners,
  "cat-dates": catDates,
  "cat-oils": catOils,
  "cat-nuts": catNuts,
};

const staggerContainer = {
  hidden: {},
  visible: { transition: { staggerChildren: 0.12, delayChildren: 0.2 } },
};

const staggerItem = {
  hidden: { opacity: 0, y: 24 },
  visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: [0.25, 0.1, 0.25, 1] as const } },
};

const heroSlides = [heroSlide1, heroSlide2, heroSlide3];

// Preload next slide image
const preloadImage = (src: string) => {
  const img = new Image();
  img.src = src;
};

const Index = () => {
  const { t } = useTranslation();
  const [currentSlide, setCurrentSlide] = useState(0);

  const whyChooseUs = [
    { icon: Leaf, title: t("whyChooseUs.natural"), desc: t("whyChooseUs.naturalDesc") },
    { icon: Truck, title: t("whyChooseUs.farmDirect"), desc: t("whyChooseUs.farmDirectDesc") },
    { icon: Shield, title: t("whyChooseUs.noChemicals"), desc: t("whyChooseUs.noChemicalsDesc") },
    { icon: Truck, title: t("whyChooseUs.freeDelivery"), desc: t("whyChooseUs.freeDeliveryDesc") },
  ];

  const healthBenefits = [
    { icon: Heart, title: t("home.heartHealth"), desc: t("home.heartHealthDesc") },
    { icon: Shield, title: t("home.immunityBoost"), desc: t("home.immunityBoostDesc") },
    { icon: Zap, title: t("home.energyVitality"), desc: t("home.energyVitalityDesc") },
    { icon: Award, title: t("home.weightManagement"), desc: t("home.weightManagementDesc") },
  ];

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentSlide((prev) => {
        const next = (prev + 1) % heroSlides.length;
        // Preload the slide after next
        preloadImage(heroSlides[(next + 1) % heroSlides.length]);
        return next;
      });
    }, 5000);
    return () => clearInterval(interval);
  }, []);

  return (
    <div>
      {/* Hero Section */}
      <section id="hero-section" className="relative overflow-hidden aspect-[10/3] sm:aspect-auto sm:min-h-[280px] md:min-h-[400px]">
        <AnimatePresence initial={false}>
          <motion.img
            key={currentSlide}
            src={heroSlides[currentSlide]}
            alt={t("hero.alt")}
            className="absolute inset-0 w-full h-full object-cover"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ opacity: { duration: 1, ease: "easeInOut" } }}
          />
        </AnimatePresence>
        <div className="absolute bottom-3 sm:bottom-5 right-4 sm:right-8 flex gap-2 z-10">
          {heroSlides.map((_, i) => (
            <button
              key={i}
              onClick={() => setCurrentSlide(i)}
              className={`w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full transition-all duration-500 ${
                i === currentSlide ? "bg-white w-6 sm:w-8" : "bg-white/40 hover:bg-white/60"
              }`}
              aria-label={`Go to slide ${i + 1}`}
            />
          ))}
        </div>
      </section>

      {/* Why Choose Us */}
      <section id="why-choose-us" className="py-5 sm:py-8 bg-muted/60 border-b border-border/40">
        <div className="container-custom">
          <motion.div variants={staggerContainer} initial="hidden" whileInView="visible" viewport={{ once: true }} className="grid grid-cols-4 gap-2 sm:gap-6">
            {whyChooseUs.map((item) => (
              <motion.div key={item.title} variants={staggerItem} className="text-center group">
                <motion.div whileHover={{ scale: 1.15 }} transition={{ type: "spring", stiffness: 300 }} className="inline-flex items-center justify-center w-9 h-9 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-primary/10 text-primary mb-1.5 sm:mb-3 group-hover:bg-primary group-hover:text-primary-foreground transition-colors duration-300">
                  <item.icon className="h-4 w-4 sm:h-6 sm:w-6" />
                </motion.div>
                <h3 className="font-heading text-[10px] sm:text-sm font-semibold mb-0.5 sm:mb-1 leading-tight">{item.title}</h3>
                <p className="font-body text-[9px] sm:text-xs text-muted-foreground leading-tight hidden sm:block">{item.desc}</p>
              </motion.div>
            ))}
          </motion.div>
        </div>
      </section>

      {/* Featured Categories */}
      <section className="py-8 sm:py-12 overflow-hidden">
        <div className="container-custom">
          <motion.div initial={{ opacity: 0, y: 16 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} className="text-center mb-6 sm:mb-8">
            <h2 className="font-heading text-2xl sm:text-3xl font-bold text-foreground">{t("categories.title")}</h2>
          </motion.div>
        </div>
        <div className="relative w-full">
          <div className="flex animate-marquee gap-6 sm:gap-10 w-max hover:[animation-play-state:paused]">
            {[...categories, ...categories].map((cat, i) => (
              <Link key={`${cat.slug}-${i}`} to={`/shop?category=${cat.slug}`} className="group flex flex-col items-center gap-2 sm:gap-3 flex-shrink-0">
                <div className="w-20 h-20 sm:w-28 sm:h-28 lg:w-32 lg:h-32 rounded-full overflow-hidden border-2 border-border group-hover:border-primary shadow-sm group-hover:shadow-lg transition-all duration-300">
                  <img src={categoryImages[cat.image]} alt={cat.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                </div>
                <h3 className="font-heading text-xs sm:text-sm font-semibold text-foreground text-center leading-tight max-w-[90px] sm:max-w-[120px] group-hover:text-primary transition-colors duration-300">{cat.name}</h3>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Best Sellers */}
      <section className="section-padding bg-muted/50">
        <div className="container-custom">
          <div className="flex items-end justify-between mb-6 sm:mb-8">
            <motion.div initial={{ opacity: 0, x: -16 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }}>
              <p className="font-accent text-xl text-accent mb-1">{t("home.popularPicks")}</p>
              <h2 className="font-heading text-3xl sm:text-4xl font-bold text-foreground">{t("home.bestSellers")}</h2>
            </motion.div>
            <Link to="/shop" className="hidden sm:flex items-center gap-1 font-body text-sm font-medium text-primary hover:underline group">
              {t("home.viewAll")} <ChevronRight className="h-4 w-4 group-hover:translate-x-0.5 transition-transform" />
            </Link>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
            {products.slice(0, 8).map((product, i) => (
              <ProductCard key={product.id} product={product} index={i} />
            ))}
          </div>
          <div className="mt-8 text-center sm:hidden">
            <Link to="/shop" className="inline-flex items-center gap-1 font-body text-sm font-medium text-primary hover:underline">
              {t("home.viewAllProducts")} <ChevronRight className="h-4 w-4" />
            </Link>
          </div>
        </div>
      </section>

      {/* All Products */}
      <section className="section-padding">
        <div className="container-custom">
          <div className="flex items-end justify-between mb-6 sm:mb-8">
            <motion.div initial={{ opacity: 0, x: -16 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }}>
              <p className="font-accent text-xl text-accent mb-1">{t("home.ourCollection")}</p>
              <h2 className="font-heading text-3xl sm:text-4xl font-bold text-foreground">{t("home.allProducts")}</h2>
            </motion.div>
            <Link to="/shop" className="hidden sm:flex items-center gap-1 font-body text-sm font-medium text-primary hover:underline group">
              {t("home.browseAll")} <ChevronRight className="h-4 w-4 group-hover:translate-x-0.5 transition-transform" />
            </Link>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
            {products.map((product, i) => (
              <ProductCard key={product.id} product={product} index={i} />
            ))}
          </div>
          <div className="mt-8 text-center sm:hidden">
            <Link to="/shop" className="inline-flex items-center gap-1 font-body text-sm font-medium text-primary hover:underline">
              {t("home.browseAllProducts")} <ChevronRight className="h-4 w-4" />
            </Link>
          </div>
        </div>
      </section>

      {/* Health Benefits */}
      <section className="section-padding bg-card border-y border-border/40">
        <div className="container-custom">
          <motion.div initial={{ opacity: 0, y: 16 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} className="text-center mb-6 sm:mb-8">
            <p className="font-accent text-xl text-accent mb-1">{t("home.liveBetter")}</p>
            <h2 className="font-heading text-3xl sm:text-4xl font-bold text-foreground">{t("home.discoverPower")}</h2>
          </motion.div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {healthBenefits.map((b, i) => (
              <motion.div key={b.title} initial={{ opacity: 0, y: 24 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} whileHover={{ y: -6 }} className="bg-background border border-border rounded-2xl p-6 text-center hover:shadow-lg hover:shadow-primary/5 transition-shadow duration-500 cursor-default">
                <motion.div whileHover={{ rotate: [0, -8, 8, 0] }} transition={{ duration: 0.5 }} className="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-primary/15 to-primary/5 text-primary mb-4">
                  <b.icon className="h-6 w-6" />
                </motion.div>
                <h3 className="font-heading text-lg font-semibold mb-2">{b.title}</h3>
                <p className="font-body text-sm text-muted-foreground">{b.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="section-padding bg-gradient-earthy text-primary-foreground relative overflow-hidden">
        <div className="absolute inset-0 opacity-[0.03]" style={{ backgroundImage: 'url("data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")' }} />
        <div className="container-custom relative">
          <motion.div initial={{ opacity: 0, y: 16 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} className="text-center mb-6 sm:mb-8">
            <p className="font-accent text-xl text-golden-light mb-1">{t("home.happyCustomers")}</p>
            <h2 className="font-heading text-3xl sm:text-4xl font-bold">{t("home.whatCustomersSay")}</h2>
          </motion.div>
        </div>
        <div className="relative w-full">
          <div className="flex animate-marquee-slow gap-6 w-max hover:[animation-play-state:paused]">
            {[...testimonials, ...testimonials].map((te, i) => (
              <div key={`${te.id}-${i}`} className="bg-primary-foreground/10 backdrop-blur-sm rounded-2xl p-6 border border-primary-foreground/5 hover:bg-primary-foreground/15 transition-all duration-300 w-[320px] sm:w-[360px] flex-shrink-0">
                <div className="flex gap-0.5 mb-3">
                  {Array.from({ length: te.rating }).map((_, j) => (
                    <Star key={j} className="h-4 w-4 text-accent fill-accent" />
                  ))}
                </div>
                <p className="font-body text-sm leading-relaxed mb-4 opacity-90 italic">"{te.text}"</p>
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-full bg-gradient-to-br from-accent/30 to-primary-foreground/20 flex items-center justify-center font-heading font-bold text-sm">
                    {te.name.charAt(0)}
                  </div>
                  <div>
                    <p className="font-body text-sm font-semibold">{te.name}</p>
                    {te.verified && <p className="font-body text-[11px] text-accent flex items-center gap-1"><BadgeCheck className="h-3 w-3" /> {t("home.verifiedPurchase")}</p>}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Blog Preview */}
      <section className="section-padding bg-muted/50">
        <div className="container-custom">
          <div className="flex items-end justify-between mb-6 sm:mb-8">
            <motion.div initial={{ opacity: 0, x: -16 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }}>
              <p className="font-accent text-xl text-accent mb-1">{t("home.fromOurKitchen")}</p>
              <h2 className="font-heading text-3xl sm:text-4xl font-bold text-foreground">{t("home.recipesTips")}</h2>
            </motion.div>
            <Link to="/blog" className="hidden sm:flex items-center gap-1 font-body text-sm font-medium text-primary hover:underline group">
              {t("home.viewAll")} <ChevronRight className="h-4 w-4 group-hover:translate-x-0.5 transition-transform" />
            </Link>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {blogPosts.slice(1, 4).map((post, i) => (
              <motion.div key={post.title} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} whileHover={{ y: -4 }} className="bg-card border border-border rounded-xl overflow-hidden hover:shadow-lg hover:shadow-primary/5 transition-all duration-500 group flex flex-col">
                <div className="h-36 overflow-hidden">
                  <img src={post.image} alt={post.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                </div>
                <div className="p-4 flex flex-col flex-1">
                  <div className="flex items-center gap-2 mb-2">
                    <span className="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-xs font-body font-medium">{post.category}</span>
                    <span className="flex items-center gap-1 text-xs text-muted-foreground font-body"><Clock className="h-3 w-3" />{post.readTime}</span>
                  </div>
                  <h3 className="font-heading text-base font-semibold text-foreground mb-1.5 group-hover:text-primary transition-colors duration-300 line-clamp-2">{post.title}</h3>
                  <p className="font-body text-xs text-muted-foreground mb-3 flex-1 line-clamp-2">{post.excerpt}</p>
                  <Link to={`/blog/${post.slug}`} className="inline-flex items-center gap-1 text-xs font-body font-medium text-primary hover:underline group/link">
                    {t("home.readMore")} <ArrowRight className="h-3 w-3 group-hover/link:translate-x-1 transition-transform" />
                  </Link>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Newsletter */}
      <section className="section-padding">
        <div className="container-custom">
          <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} className="relative bg-gradient-to-br from-primary/5 via-muted to-accent/5 rounded-3xl p-8 sm:p-12 text-center max-w-2xl mx-auto border border-border/60 overflow-hidden">
            <Sparkles className="absolute top-6 right-8 h-5 w-5 text-accent/30 animate-pulse" />
            <Sparkles className="absolute bottom-8 left-10 h-4 w-4 text-primary/20 animate-pulse" style={{ animationDelay: "1s" }} />
            <p className="font-accent text-xl text-accent mb-1">{t("home.stayConnected")}</p>
            <h2 className="font-heading text-2xl sm:text-3xl font-bold text-foreground mb-3">{t("home.get10Off")}</h2>
            <p className="font-body text-sm text-muted-foreground mb-6">{t("home.subscribeDesc")}</p>
            <div className="flex w-full max-w-md mx-auto">
              <input type="email" placeholder={t("home.enterEmail")} className="flex-1 min-w-0 px-4 py-3 rounded-l-xl bg-background border border-border border-r-0 text-foreground placeholder:text-muted-foreground text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 font-body" />
              <button className="px-6 py-3 rounded-r-xl bg-primary text-primary-foreground font-body font-semibold text-sm hover:shadow-lg hover:shadow-primary/25 transition-all duration-300">
                {t("home.subscribe")}
              </button>
            </div>
            <p className="font-body text-[11px] text-muted-foreground/60 mt-3">{t("home.noSpam")}</p>
          </motion.div>
        </div>
      </section>
    </div>
  );
};

export default Index;

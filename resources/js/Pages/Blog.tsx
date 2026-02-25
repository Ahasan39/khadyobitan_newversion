import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { Clock, ChefHat, ArrowRight, Leaf } from "lucide-react";
import { blogPosts } from "@/data/blogPosts";
import { useTranslation } from "react-i18next";

const recipeHighlights = [
  { title: "Honey Turmeric Latte", time: "5 min", difficulty: "Easy", products: ["Raw Organic Honey", "Organic Turmeric"] },
  { title: "Black Rice Sushi Bowl", time: "30 min", difficulty: "Medium", products: ["Organic Black Rice", "Sesame Oil"] },
  { title: "Date Energy Balls", time: "15 min", difficulty: "Easy", products: ["Medjool Dates", "Black Chia Seeds"] },
];

const Blog = () => {
  const { t } = useTranslation();

  return (
    <div>
      <section className="bg-gradient-earthy text-primary-foreground section-padding">
        <div className="container-custom text-center">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <p className="font-accent text-xl text-accent mb-2">{t("blog.fromOurKitchen")}</p>
            <h1 className="font-heading text-4xl sm:text-5xl font-bold mb-4">{t("blog.title")}</h1>
            <p className="font-body text-base opacity-80 max-w-lg mx-auto">{t("blog.heroDesc")}</p>
          </motion.div>
        </div>
      </section>

      <section className="section-padding bg-muted">
        <div className="container-custom">
          <div className="text-center mb-10">
            <p className="font-accent text-xl text-accent mb-1">{t("blog.quickEasy")}</p>
            <h2 className="font-heading text-3xl font-bold text-foreground">{t("blog.featuredRecipes")}</h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {recipeHighlights.map((r, i) => (
              <motion.div key={r.title} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="bg-card border border-border rounded-xl p-6 hover:shadow-md transition-shadow">
                <div className="flex items-center gap-2 mb-3">
                  <ChefHat className="h-5 w-5 text-primary" />
                  <span className="font-body text-xs font-medium text-muted-foreground">{r.difficulty}</span>
                  <span className="text-muted-foreground">·</span>
                  <Clock className="h-4 w-4 text-muted-foreground" />
                  <span className="font-body text-xs text-muted-foreground">{r.time}</span>
                </div>
                <h3 className="font-heading text-lg font-semibold text-foreground mb-3">{r.title}</h3>
                <div className="flex flex-wrap gap-2">
                  {r.products.map((p) => (
                    <span key={p} className="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary rounded-full text-xs font-body"><Leaf className="h-3 w-3" /> {p}</span>
                  ))}
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-custom">
          <div className="text-center mb-12">
            <p className="font-accent text-xl text-accent mb-1">{t("blog.latestArticles")}</p>
            <h2 className="font-heading text-3xl font-bold text-foreground">{t("blog.readLearn")}</h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {blogPosts.map((post, i) => (
              <motion.article key={post.id} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.08 }} className="bg-card border border-border rounded-xl overflow-hidden hover:shadow-md transition-shadow group">
                <Link to={`/blog/${post.slug}`}>
                  <div className="aspect-video bg-muted overflow-hidden">
                    <img src={post.image} alt={post.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                  </div>
                </Link>
                <div className="p-5">
                  <div className="flex items-center gap-2 mb-2">
                    <span className="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-xs font-body font-medium">{post.category}</span>
                    <span className="text-xs text-muted-foreground font-body">{post.readTime}</span>
                  </div>
                  <h3 className="font-heading text-base font-semibold text-foreground mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                    <Link to={`/blog/${post.slug}`}>{post.title}</Link>
                  </h3>
                  <p className="font-body text-sm text-muted-foreground line-clamp-2 mb-3">{post.excerpt}</p>
                  <div className="flex items-center justify-between">
                    <span className="text-xs text-muted-foreground font-body">{post.date}</span>
                    <Link to={`/blog/${post.slug}`} className="inline-flex items-center gap-1 text-primary text-sm font-body font-medium group-hover:underline">
                      {t("blog.read")} <ArrowRight className="h-3 w-3" />
                    </Link>
                  </div>
                </div>
              </motion.article>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};

export default Blog;

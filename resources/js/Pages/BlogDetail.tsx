import { useParams, Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowLeft, Clock, Calendar, ChevronRight } from "lucide-react";
import { blogPosts } from "@/data/blogPosts";
import { useTranslation } from "react-i18next";

const BlogDetail = () => {
  const { t } = useTranslation();
  const { slug } = useParams<{ slug: string }>();
  const post = blogPosts.find((p) => p.slug === slug);

  if (!post) {
    return (
    <>
      <Head title="Blog - Khadyobitan" />
      <div className="min-h-[60vh] flex flex-col items-center justify-center">
        <h1 className="font-heading text-3xl font-bold text-foreground mb-4">{t("blog.postNotFound")}</h1>
        <Link href="/blog" className="text-primary font-body hover:underline inline-flex items-center gap-1">
          <ArrowLeft className="h-4 w-4" /> {t("blog.backToBlog")}
        </Link>
      </div>
    );
  }

  const relatedPosts = blogPosts.filter((p) => p.id !== post.id).slice(0, 3);

  const renderContent = (content: string) => {
    const lines = content.trim().split("\n");
    const elements: JSX.Element[] = [];
    let i = 0;

    while (i < lines.length) {
      const line = lines[i];
      if (line.startsWith("### ")) {
        elements.push(<h3 key={i} className="font-heading text-xl font-semibold text-foreground mt-8 mb-3">{line.slice(4)}</h3>);
      } else if (line.startsWith("## ")) {
        elements.push(<h2 key={i} className="font-heading text-2xl font-bold text-foreground mt-10 mb-4">{line.slice(3)}</h2>);
      } else if (line.startsWith("#### ")) {
        elements.push(<h4 key={i} className="font-heading text-lg font-semibold text-foreground mt-6 mb-2">{line.slice(5)}</h4>);
      } else if (line.startsWith("- ")) {
        const items: string[] = [];
        while (i < lines.length && lines[i].startsWith("- ")) { items.push(lines[i].slice(2)); i++; }
        elements.push(<ul key={`list-${i}`} className="list-disc list-inside space-y-1.5 font-body text-muted-foreground mb-4 ml-2">{items.map((item, j) => <li key={j} dangerouslySetInnerHTML={{ __html: formatInline(item) }} />)}</ul>);
        continue;
      } else if (line.startsWith("| ") && lines[i + 1]?.startsWith("|--")) {
        const headers = line.split("|").filter(Boolean).map(h => h.trim());
        i++;
        const rows: string[][] = [];
        i++;
        while (i < lines.length && lines[i].startsWith("| ")) { rows.push(lines[i].split("|").filter(Boolean).map(c => c.trim())); i++; }
        elements.push(
          <div key={`table-${i}`} className="overflow-x-auto my-6">
            <table className="w-full text-sm font-body border border-border rounded-lg overflow-hidden">
              <thead><tr className="bg-muted">{headers.map((h, j) => <th key={j} className="px-4 py-2.5 text-left font-semibold text-foreground">{h}</th>)}</tr></thead>
              <tbody>{rows.map((row, j) => <tr key={j} className="border-t border-border">{row.map((cell, k) => <td key={k} className="px-4 py-2.5 text-muted-foreground">{cell}</td>)}</tr>)}</tbody>
            </table>
          </div>
        );
        continue;
      } else if (line.startsWith("---")) {
        elements.push(<hr key={i} className="my-8 border-border" />);
      } else if (line.trim() === "") {
        // skip
      } else if (/^\d+\.\s/.test(line)) {
        const items: string[] = [];
        while (i < lines.length && /^\d+\.\s/.test(lines[i])) { items.push(lines[i].replace(/^\d+\.\s/, "")); i++; }
        elements.push(<ol key={`ol-${i}`} className="list-decimal list-inside space-y-1.5 font-body text-muted-foreground mb-4 ml-2">{items.map((item, j) => <li key={j} dangerouslySetInnerHTML={{ __html: formatInline(item) }} />)}</ol>);
        continue;
      } else {
        elements.push(<p key={i} className="font-body text-muted-foreground leading-relaxed mb-4" dangerouslySetInnerHTML={{ __html: formatInline(line) }} />);
      }
      i++;
    }
    return elements;
  };

  const formatInline = (text: string): string => {
    return text.replace(/\*\*(.+?)\*\*/g, '<strong class="text-foreground font-semibold">$1</strong>').replace(/\*(.+?)\*/g, '<em>$1</em>');
  };

  return (
    <>
      <Head title="Blog - Khadyobitan" />
      <div>
      <div className="relative h-64 sm:h-80 md:h-96 overflow-hidden">
        <img src={post.image} alt={post.title} className="w-full h-full object-cover" />
        <div className="absolute inset-0 bg-gradient-to-t from-foreground/80 via-foreground/30 to-transparent" />
        <div className="absolute bottom-0 left-0 right-0 p-6 sm:p-10">
          <div className="container-custom">
            <Link href="/blog" className="inline-flex items-center gap-1 text-primary-foreground/80 font-body text-sm mb-3 hover:text-primary-foreground transition-colors">
              <ArrowLeft className="h-4 w-4" /> {t("blog.backToBlog")}
            </Link>
            <motion.h1 initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="font-heading text-2xl sm:text-3xl md:text-4xl font-bold text-primary-foreground max-w-3xl">
              {post.title}
            </motion.h1>
          </div>
        </div>
      </div>

      <section className="section-padding">
        <div className="container-custom max-w-3xl">
          <motion.div initial={{ opacity: 0, y: 10 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.1 }}>
            <div className="flex flex-wrap items-center gap-4 mb-8 pb-6 border-b border-border">
              <span className="px-3 py-1 bg-primary/10 text-primary rounded-full text-sm font-body font-medium">{post.category}</span>
              <span className="flex items-center gap-1.5 text-sm text-muted-foreground font-body"><Calendar className="h-4 w-4" />{post.date}</span>
              <span className="flex items-center gap-1.5 text-sm text-muted-foreground font-body"><Clock className="h-4 w-4" />{post.readTime}</span>
            </div>
            <div className="prose-custom">{renderContent(post.content)}</div>
          </motion.div>
        </div>
      </section>

      <section className="section-padding bg-muted/50">
        <div className="container-custom">
          <h2 className="font-heading text-2xl font-bold text-foreground mb-6">{t("blog.youMayAlsoLike")}</h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {relatedPosts.map((rp) => (
              <Link key={rp.id} href={`/blog/${rp.slug}`} className="bg-card border border-border rounded-xl overflow-hidden hover:shadow-md transition-shadow group">
                <div className="aspect-video overflow-hidden">
                  <img src={rp.image} alt={rp.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                </div>
                <div className="p-4">
                  <span className="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-xs font-body font-medium">{rp.category}</span>
                  <h3 className="font-heading text-sm font-semibold text-foreground mt-2 line-clamp-2 group-hover:text-primary transition-colors">{rp.title}</h3>
                  <span className="inline-flex items-center gap-1 text-primary text-xs font-body font-medium mt-2">{t("blog.readMore")} <ChevronRight className="h-3 w-3" /></span>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>
    </div>
    </>
  );
};
export default BlogDetail;


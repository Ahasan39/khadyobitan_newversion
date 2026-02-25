import { useState } from "react";
import { motion } from "framer-motion";
import { Head, Link } from "@inertiajs/react";
import { Phone, Mail, MapPin, Clock, ChevronRight, Send } from "lucide-react";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

const Contact = () => {
  const { t } = useTranslation();
  const [form, setForm] = useState({ name: "", email: "", phone: "", subject: "", message: "" });
  const update = (field: string, value: string) => setForm((f) => ({ ...f, [field]: value }));

  const contactInfo = [
    { icon: Phone, label: t("contact.phone"), value: "+880 1234-567890", href: "tel:+8801234567890" },
    { icon: Mail, label: t("contact.email"), value: "hello@khadyobitan.com", href: "mailto:hello@khadyobitan.com" },
    { icon: MapPin, label: t("contact.address"), value: "Gulshan-2, Dhaka, Bangladesh", href: "#" },
    { icon: Clock, label: t("contact.hours"), value: "Sat-Thu: 9AM - 8PM", href: "#" },
  ];

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!form.name || !form.email || !form.message) { toast.error(t("contact.fillRequired")); return; }
    toast.success(t("contact.messageSent"));
    setForm({ name: "", email: "", phone: "", subject: "", message: "" });
  };

  return (
    <>
      <Head title="Contact Us - Khadyobitan" />
      <div>
      <section className="section-padding bg-gradient-earthy text-primary-foreground">
        <div className="container-custom">
          <nav className="flex items-center gap-2 text-sm font-body text-primary-foreground/60 mb-8">
            <Link href="/" className="hover:text-primary-foreground">{t("common.home")}</Link>
            <ChevronRight className="h-3 w-3" />
            <span>{t("nav.contact")}</span>
          </nav>
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <p className="font-accent text-2xl text-golden-light mb-2">{t("contact.getInTouch")}</p>
            <h1 className="font-heading text-4xl sm:text-5xl font-bold">{t("contact.heroTitle")}</h1>
          </motion.div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-custom">
          <div className="grid grid-cols-1 lg:grid-cols-5 gap-12">
            <div className="lg:col-span-2">
              <h2 className="font-heading text-2xl font-bold mb-6">{t("contact.contactInfo")}</h2>
              <div className="space-y-5">
                {contactInfo.map((c) => (
                  <a key={c.label} href={c.href} className="flex items-start gap-4 group">
                    <div className="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-primary-foreground transition-colors"><c.icon className="h-5 w-5" /></div>
                    <div>
                      <p className="font-body text-xs text-muted-foreground uppercase tracking-wider">{c.label}</p>
                      <p className="font-body text-sm font-medium">{c.value}</p>
                    </div>
                  </a>
                ))}
              </div>
            </div>

            <div className="lg:col-span-3">
              <h2 className="font-heading text-2xl font-bold mb-6">{t("contact.sendMessage")}</h2>
              <form onSubmit={handleSubmit} className="space-y-4">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block font-body text-sm font-medium mb-1">{t("contact.nameStar")}</label>
                    <input value={form.name} onChange={(e) => update("name", e.target.value)} className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder={t("contact.yourName")} />
                  </div>
                  <div>
                    <label className="block font-body text-sm font-medium mb-1">{t("contact.emailStar")}</label>
                    <input value={form.email} onChange={(e) => update("email", e.target.value)} type="email" className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder="email@example.com" />
                  </div>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block font-body text-sm font-medium mb-1">{t("contact.phonePlain")}</label>
                    <input value={form.phone} onChange={(e) => update("phone", e.target.value)} className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder="01XXXXXXXXX" />
                  </div>
                  <div>
                    <label className="block font-body text-sm font-medium mb-1">{t("contact.subject")}</label>
                    <input value={form.subject} onChange={(e) => update("subject", e.target.value)} className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder={t("contact.howCanWeHelp")} />
                  </div>
                </div>
                <div>
                  <label className="block font-body text-sm font-medium mb-1">{t("contact.messageStar")}</label>
                  <textarea value={form.message} onChange={(e) => update("message", e.target.value)} rows={5} className="w-full px-4 py-3 rounded-lg border border-border bg-background font-body text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none" placeholder={t("contact.yourMessage")} />
                </div>
                <button type="submit" className="inline-flex items-center gap-2 px-8 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity">
                  <Send className="h-4 w-4" /> {t("contact.send")}
                </button>
              </form>
            </div>
          </div>
        </div>
      </section>
    </div>
    </>
  );
};
export default Contact;


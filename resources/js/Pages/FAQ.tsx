import { motion } from "framer-motion";
import { Link } from "react-router-dom";
import { ChevronRight } from "lucide-react";
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion";
import { useTranslation } from "react-i18next";

const FAQ = () => {
  const { t } = useTranslation();

  const faqs = [
    {
      category: t("faq.productsQuality"),
      items: [
        { q: t("faq.q1"), a: t("faq.a1") },
        { q: t("faq.q2"), a: t("faq.a2") },
        { q: t("faq.q3"), a: t("faq.a3") },
        { q: t("faq.q4"), a: t("faq.a4") },
      ],
    },
    {
      category: t("faq.ordersShipping"),
      items: [
        { q: t("faq.q5"), a: t("faq.a5") },
        { q: t("faq.q6"), a: t("faq.a6") },
        { q: t("faq.q7"), a: t("faq.a7") },
        { q: t("faq.q8"), a: t("faq.a8") },
      ],
    },
    {
      category: t("faq.paymentsReturns"),
      items: [
        { q: t("faq.q9"), a: t("faq.a9") },
        { q: t("faq.q10"), a: t("faq.a10") },
        { q: t("faq.q11"), a: t("faq.a11") },
        { q: t("faq.q12"), a: t("faq.a12") },
      ],
    },
  ];

  return (
    <div>
      <section className="section-padding bg-gradient-earthy text-primary-foreground">
        <div className="container-custom">
          <nav className="flex items-center gap-2 text-sm font-body text-primary-foreground/60 mb-8">
            <Link to="/" className="hover:text-primary-foreground">{t("common.home")}</Link>
            <ChevronRight className="h-3 w-3" />
            <span>FAQ</span>
          </nav>
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
            <p className="font-accent text-2xl text-golden-light mb-2">{t("faq.helpCenter")}</p>
            <h1 className="font-heading text-4xl sm:text-5xl font-bold">{t("faq.title")}</h1>
          </motion.div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-custom max-w-3xl">
          {faqs.map((group, gi) => (
            <motion.div
              key={gi}
              initial={{ opacity: 0, y: 15 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: gi * 0.1 }}
              className="mb-10"
            >
              <h2 className="font-heading text-xl font-bold mb-4">{group.category}</h2>
              <Accordion type="single" collapsible className="space-y-2">
                {group.items.map((item, i) => (
                  <AccordionItem key={i} value={`${gi}-${i}`} className="border border-border rounded-xl px-4 data-[state=open]:bg-muted/50">
                    <AccordionTrigger className="font-body text-sm font-medium text-left py-4 hover:no-underline">
                      {item.q}
                    </AccordionTrigger>
                    <AccordionContent className="font-body text-sm text-muted-foreground pb-4 leading-relaxed">
                      {item.a}
                    </AccordionContent>
                  </AccordionItem>
                ))}
              </Accordion>
            </motion.div>
          ))}

          <div className="bg-muted rounded-2xl p-8 text-center mt-12">
            <h3 className="font-heading text-xl font-bold mb-2">{t("faq.stillHaveQuestions")}</h3>
            <p className="font-body text-sm text-muted-foreground mb-4">{t("faq.supportTeam")}</p>
            <Link to="/contact" className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity">
              {t("faq.contactUs")}
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
};

export default FAQ;

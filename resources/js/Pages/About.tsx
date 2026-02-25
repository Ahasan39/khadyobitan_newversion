import { motion } from "framer-motion";
import { Head, Link } from "@inertiajs/react";
import { Leaf, Heart, Shield, Users, Globe, Award, ArrowRight, ChevronRight } from "lucide-react";
import aboutMission from "@/assets/about-mission.jpg";
import aboutFarm from "@/assets/about-farm.jpg";
import { useTranslation } from "react-i18next";

const About = () => {
  const { t } = useTranslation();

  const values = [
    { icon: Leaf, title: t("about.organic"), desc: t("about.organicDesc") },
    { icon: Heart, title: t("about.healthFirst"), desc: t("about.healthFirstDesc") },
    { icon: Shield, title: t("about.labTestedValue"), desc: t("about.labTestedDesc") },
    { icon: Globe, title: t("about.sustainablySourced"), desc: t("about.sustainablySourcedDesc") },
    { icon: Users, title: t("about.communityDriven"), desc: t("about.communityDrivenDesc") },
    { icon: Award, title: t("about.premiumQuality"), desc: t("about.premiumQualityDesc") },
  ];

  const milestones = [
    { year: "2020", event: t("about.milestone2020") },
    { year: "2021", event: t("about.milestone2021") },
    { year: "2022", event: t("about.milestone2022") },
    { year: "2023", event: t("about.milestone2023") },
    { year: "2024", event: t("about.milestone2024") },
  ];

  const stats = [
    { value: "50+", label: t("about.partnerFarms") },
    { value: "10K+", label: t("about.happyCustomers") },
    { value: "100+", label: t("about.organicProducts") },
    { value: "5★", label: t("about.averageRating") },
  ];

  return (
    <>
      <Head title="About Us - Khadyobitan" />
      <div>
      <section className="relative overflow-hidden">
        <div className="absolute inset-0">
          <img src={aboutFarm} alt="Organic farmland" className="w-full h-full object-cover" />
          <div className="absolute inset-0 bg-gradient-to-r from-foreground/90 via-foreground/75 to-foreground/50" />
        </div>
        <div className="relative container-custom py-20 sm:py-28">
          <nav className="flex items-center gap-2 text-sm font-body text-primary-foreground/60 mb-8">
            <Link href="/" className="hover:text-primary-foreground transition-colors">{t("common.home")}</Link>
            <ChevronRight className="h-3 w-3" />
            <span className="text-primary-foreground/80">{t("footer.aboutUs")}</span>
          </nav>
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="max-w-xl">
            <p className="font-accent text-2xl text-accent mb-2">{t("about.ourStory")}</p>
            <h1 className="font-heading text-4xl sm:text-5xl font-bold text-primary-foreground mb-5">{t("about.heroTitle")}</h1>
            <p className="font-body text-sm sm:text-base text-primary-foreground/80 leading-relaxed">{t("about.heroDesc")}</p>
          </motion.div>
        </div>
      </section>

      <section className="bg-primary">
        <div className="container-custom py-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            {stats.map((s, i) => (
              <motion.div key={i} initial={{ opacity: 0, y: 10 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }}>
                <p className="font-heading text-2xl sm:text-3xl font-bold text-primary-foreground">{s.value}</p>
                <p className="font-body text-xs sm:text-sm text-primary-foreground/70 mt-1">{s.label}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-custom">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <motion.div initial={{ opacity: 0, x: -20 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }}>
              <p className="font-accent text-xl text-accent mb-1">{t("about.ourMission")}</p>
              <h2 className="font-heading text-3xl font-bold text-foreground mb-4">{t("about.missionTitle")}</h2>
              <p className="font-body text-sm text-muted-foreground leading-relaxed mb-4">{t("about.missionDesc1")}</p>
              <p className="font-body text-sm text-muted-foreground leading-relaxed mb-6">{t("about.missionDesc2")}</p>
              <Link href="/shop" className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity">
                {t("about.exploreProducts")} <ArrowRight className="h-4 w-4" />
              </Link>
            </motion.div>
            <motion.div initial={{ opacity: 0, x: 20 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }} className="relative">
              <div className="rounded-2xl overflow-hidden shadow-xl">
                <img src={aboutMission} alt="Organic products from Khadyobitan" className="w-full h-auto object-cover" />
              </div>
              <div className="absolute -bottom-4 -left-4 bg-accent text-primary-foreground px-5 py-3 rounded-xl shadow-lg hidden sm:block">
                <p className="font-heading text-lg font-bold">{t("about.since2020")}</p>
                <p className="font-body text-xs opacity-80">{t("about.servingOrganic")}</p>
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      <section className="section-padding bg-muted/50">
        <div className="container-custom">
          <div className="text-center mb-10">
            <p className="font-accent text-xl text-accent mb-1">{t("about.whyKhadyobitan")}</p>
            <h2 className="font-heading text-3xl font-bold text-foreground">{t("about.coreValues")}</h2>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {values.map((v, i) => (
              <motion.div key={i} initial={{ opacity: 0, y: 15 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.08 }} className="bg-card border border-border rounded-xl p-5 hover:shadow-md transition-shadow">
                <div className="inline-flex items-center justify-center w-11 h-11 rounded-full bg-primary/10 text-primary mb-3"><v.icon className="h-5 w-5" /></div>
                <h3 className="font-heading text-base font-semibold mb-1.5">{v.title}</h3>
                <p className="font-body text-sm text-muted-foreground">{v.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="section-padding">
        <div className="container-custom max-w-2xl">
          <div className="text-center mb-10">
            <p className="font-accent text-xl text-accent mb-1">{t("about.ourJourney")}</p>
            <h2 className="font-heading text-3xl font-bold text-foreground">{t("about.growingWithPurpose")}</h2>
          </div>
          <div className="relative">
            <div className="absolute left-[31px] top-0 bottom-0 w-px bg-border hidden sm:block" />
            <div className="space-y-5">
              {milestones.map((m, i) => (
                <motion.div key={i} initial={{ opacity: 0, x: -20 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="flex gap-4 items-start">
                  <div className="shrink-0 w-16 h-12 bg-primary text-primary-foreground rounded-lg flex items-center justify-center font-heading text-base font-bold relative z-10">{m.year}</div>
                  <div className="pt-1.5"><p className="font-body text-sm text-muted-foreground leading-relaxed">{m.event}</p></div>
                </motion.div>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section className="relative overflow-hidden">
        <div className="absolute inset-0">
          <img src={aboutFarm} alt="" className="w-full h-full object-cover" />
          <div className="absolute inset-0 bg-foreground/85" />
        </div>
        <div className="relative container-custom py-16 text-center max-w-xl mx-auto">
          <motion.div initial={{ opacity: 0, y: 15 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }}>
            <h2 className="font-heading text-3xl font-bold text-primary-foreground mb-3">{t("about.readyToGoOrganic")}</h2>
            <p className="font-body text-sm text-primary-foreground/70 mb-6">{t("about.readyCta")}</p>
            <Link href="/shop" className="inline-flex items-center gap-2 px-8 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:shadow-lg hover:shadow-primary/25 transition-all">
              {t("about.shopNow")} <ArrowRight className="h-4 w-4" />
            </Link>
          </motion.div>
        </div>
      </section>
    </div>
    </>
  );
};
export default About;


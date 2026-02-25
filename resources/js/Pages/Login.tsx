import { useState } from "react";
import { Head, Link } from "@inertiajs/react";
import { motion } from "framer-motion";
import { Leaf, Eye, EyeOff, Mail, Lock, User, Phone } from "lucide-react";
import { useTranslation } from "react-i18next";

const Login = () => {
  const { t } = useTranslation();
  const [isLogin, setIsLogin] = useState(true);
  const [showPassword, setShowPassword] = useState(false);

  return (
    <>
      <Head title="Login - Khadyobitan" />
      <div className="min-h-[80vh] flex items-center justify-center section-padding bg-muted">
      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="w-full max-w-md">
        <div className="text-center mb-8">
          <Link href="/" className="inline-flex items-center gap-2 mb-4">
            <Leaf className="h-8 w-8 text-primary" />
            <span className="font-heading text-2xl font-bold text-foreground">Khadyobitan</span>
          </Link>
          <h1 className="font-heading text-2xl font-bold text-foreground">
            {isLogin ? t("login.welcomeBack") : t("login.createAccount")}
          </h1>
          <p className="font-body text-sm text-muted-foreground mt-1">
            {isLogin ? t("login.signInDesc") : t("login.registerDesc")}
          </p>
        </div>

        <div className="bg-card border border-border rounded-xl p-6 sm:p-8">
          <div className="flex rounded-lg bg-muted p-1 mb-6">
            <button onClick={() => setIsLogin(true)} className={`flex-1 py-2 text-sm font-body font-medium rounded-md transition-colors ${isLogin ? "bg-background text-foreground shadow-sm" : "text-muted-foreground"}`}>
              {t("login.signIn")}
            </button>
            <button onClick={() => setIsLogin(false)} className={`flex-1 py-2 text-sm font-body font-medium rounded-md transition-colors ${!isLogin ? "bg-background text-foreground shadow-sm" : "text-muted-foreground"}`}>
              {t("login.register")}
            </button>
          </div>

          <form className="space-y-4" onSubmit={(e) => e.preventDefault()}>
            {!isLogin && (
              <div>
                <label className="block text-sm font-body font-medium text-foreground mb-1.5">{t("login.fullName")}</label>
                <div className="relative">
                  <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <input type="text" placeholder={t("login.yourFullName")} className="w-full pl-10 pr-4 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" />
                </div>
              </div>
            )}

            <div>
              <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                {isLogin ? t("login.emailOrPhone") : t("login.email")}
              </label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <input type="email" placeholder="you@example.com" className="w-full pl-10 pr-4 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" />
              </div>
            </div>

            {!isLogin && (
              <div>
                <label className="block text-sm font-body font-medium text-foreground mb-1.5">{t("login.phoneNumber")}</label>
                <div className="relative">
                  <Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <input type="tel" placeholder="+880 1XXX-XXXXXX" className="w-full pl-10 pr-4 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" />
                </div>
              </div>
            )}

            <div>
              <label className="block text-sm font-body font-medium text-foreground mb-1.5">{t("login.password")}</label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <input type={showPassword ? "text" : "password"} placeholder="••••••••" className="w-full pl-10 pr-10 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" />
                <button type="button" onClick={() => setShowPassword(!showPassword)} className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground">
                  {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                </button>
              </div>
            </div>

            {isLogin && (
              <div className="flex items-center justify-between">
                <label className="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" className="rounded border-border text-primary focus:ring-primary" />
                  <span className="text-sm font-body text-muted-foreground">{t("login.rememberMe")}</span>
                </label>
                <a href="#" className="text-sm font-body text-primary hover:underline">{t("login.forgotPassword")}</a>
              </div>
            )}

            <button type="submit" className="w-full py-3 bg-primary text-primary-foreground rounded-lg font-body text-sm font-medium hover:opacity-90 transition-opacity">
              {isLogin ? t("login.signIn") : t("login.createAccount")}
            </button>
          </form>

          <div className="mt-6">
            <div className="relative">
              <div className="absolute inset-0 flex items-center"><div className="w-full border-t border-border" /></div>
              <div className="relative flex justify-center"><span className="bg-card px-3 text-xs text-muted-foreground font-body">{t("login.orContinueWith")}</span></div>
            </div>
            <div className="mt-4 grid grid-cols-2 gap-3">
              <button className="flex items-center justify-center gap-2 py-2.5 border border-border rounded-lg text-sm font-body font-medium text-foreground hover:bg-muted transition-colors">Google</button>
              <button className="flex items-center justify-center gap-2 py-2.5 border border-border rounded-lg text-sm font-body font-medium text-foreground hover:bg-muted transition-colors">Facebook</button>
            </div>
          </div>
        </div>
      </motion.div>
    </div>
    </>
  );
};
export default Login;


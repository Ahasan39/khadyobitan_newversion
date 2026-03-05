import React from 'react';
import { useState, useEffect } from "react";
import { Head, Link, useForm, usePage } from "@inertiajs/react";
import { motion } from "framer-motion";
import { Leaf, Eye, EyeOff, Lock, User, Phone } from "lucide-react";
import { useTranslation } from "react-i18next";
import MainLayout from "@/Components/layout/MainLayout";

interface LoginProps {
  isRegister?: boolean;
}

const Login = ({ isRegister: initialIsRegister }: LoginProps) => {
  const { t } = useTranslation();
  const { errors } = usePage().props as { errors?: Record<string, string> };
  const [isLogin, setIsLogin] = useState(!initialIsRegister);
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);

  // Login form
  const loginForm = useForm({
    phone: '',
    password: '',
  });

  // Register form
  const registerForm = useForm({
    name: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
  });

  useEffect(() => {
    if (initialIsRegister !== undefined) {
      setIsLogin(!initialIsRegister);
    }
  }, [initialIsRegister]);

  const handleLoginSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    loginForm.post('/login', {
      preserveScroll: true,
    });
  };

  const handleRegisterSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    registerForm.post('/register', {
      preserveScroll: true,
    });
  };

  return (
    <MainLayout>
      <Head title={isLogin ? "Login - Khadyobitan" : "Register - Khadyobitan"} />
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
              <button 
                type="button"
                onClick={() => setIsLogin(true)} 
                className={`flex-1 py-2 text-sm font-body font-medium rounded-md transition-colors ${isLogin ? "bg-background text-foreground shadow-sm" : "text-muted-foreground"}`}
              >
                {t("login.signIn")}
              </button>
              <button 
                type="button"
                onClick={() => setIsLogin(false)} 
                className={`flex-1 py-2 text-sm font-body font-medium rounded-md transition-colors ${!isLogin ? "bg-background text-foreground shadow-sm" : "text-muted-foreground"}`}
              >
                {t("login.register")}
              </button>
            </div>

            {isLogin ? (
              /* Login Form */
              <form className="space-y-4" onSubmit={handleLoginSubmit}>
                <div>
                  <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                    {t("login.phoneNumber")}
                  </label>
                  <div className="relative">
                    <Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <input 
                      type="tel" 
                      placeholder="01XXX-XXXXXX" 
                      value={loginForm.data.phone}
                      onChange={(e) => loginForm.setData('phone', e.target.value)}
                      className="w-full pl-10 pr-4 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" 
                    />
                  </div>
                  {(errors?.phone || loginForm.errors.phone) && (
                    <p className="mt-1 text-xs text-destructive">{errors?.phone || loginForm.errors.phone}</p>
                  )}
                </div>

                <div>
                  <label className="block text-sm font-body font-medium text-foreground mb-1.5">{t("login.password")}</label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <input 
                      type={showPassword ? "text" : "password"} 
                      placeholder="••••••••" 
                      value={loginForm.data.password}
                      onChange={(e) => loginForm.setData('password', e.target.value)}
                      className="w-full pl-10 pr-10 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" 
                    />
                    <button 
                      type="button" 
                      onClick={() => setShowPassword(!showPassword)} 
                      className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                    >
                      {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                    </button>
                  </div>
                  {(errors?.password || loginForm.errors.password) && (
                    <p className="mt-1 text-xs text-destructive">{errors?.password || loginForm.errors.password}</p>
                  )}
                </div>

                <div className="flex items-center justify-between">
                  <label className="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" className="rounded border-border text-primary focus:ring-primary" />
                    <span className="text-sm font-body text-muted-foreground">{t("login.rememberMe")}</span>
                  </label>
                  <Link href="/forgot-password" className="text-sm font-body text-primary hover:underline">
                    {t("login.forgotPassword")}
                  </Link>
                </div>

                <button 
                  type="submit" 
                  disabled={loginForm.processing}
                  className="w-full py-3 bg-primary text-primary-foreground rounded-lg font-body text-sm font-medium hover:opacity-90 transition-opacity disabled:opacity-50"
                >
                  {loginForm.processing ? 'Signing in...' : t("login.signIn")}
                </button>
              </form>
            ) : (
              /* Register Form */
              <form className="space-y-4" onSubmit={handleRegisterSubmit}>
                <div>
                  <label className="block text-sm font-body font-medium text-foreground mb-1.5">{t("login.fullName")}</label>
                  <div className="relative">
                    <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <input 
                      type="text" 
                      placeholder={t("login.yourFullName")} 
                      value={registerForm.data.name}
                      onChange={(e) => registerForm.setData('name', e.target.value)}
                      className="w-full pl-10 pr-4 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" 
                    />
                  </div>
                  {registerForm.errors.name && (
                    <p className="mt-1 text-xs text-destructive">{registerForm.errors.name}</p>
                  )}
                </div>

                <div>
                  <label className="block text-sm font-body font-medium text-foreground mb-1.5">{t("login.phoneNumber")}</label>
                  <div className="relative">
                    <Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <input 
                      type="tel" 
                      placeholder="01XXX-XXXXXX" 
                      value={registerForm.data.phone}
                      onChange={(e) => registerForm.setData('phone', e.target.value)}
                      className="w-full pl-10 pr-4 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" 
                    />
                  </div>
                  {registerForm.errors.phone && (
                    <p className="mt-1 text-xs text-destructive">{registerForm.errors.phone}</p>
                  )}
                </div>

                <div>
                  <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                    {t("login.email")} <span className="text-muted-foreground">(Optional)</span>
                  </label>
                  <div className="relative">
                    <svg className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <input 
                      type="email" 
                      placeholder="you@example.com" 
                      value={registerForm.data.email}
                      onChange={(e) => registerForm.setData('email', e.target.value)}
                      className="w-full pl-10 pr-4 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" 
                    />
                  </div>
                  {registerForm.errors.email && (
                    <p className="mt-1 text-xs text-destructive">{registerForm.errors.email}</p>
                  )}
                </div>

                <div>
                  <label className="block text-sm font-body font-medium text-foreground mb-1.5">{t("login.password")}</label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <input 
                      type={showPassword ? "text" : "password"} 
                      placeholder="••••••••" 
                      value={registerForm.data.password}
                      onChange={(e) => registerForm.setData('password', e.target.value)}
                      className="w-full pl-10 pr-10 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" 
                    />
                    <button 
                      type="button" 
                      onClick={() => setShowPassword(!showPassword)} 
                      className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                    >
                      {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                    </button>
                  </div>
                  {registerForm.errors.password && (
                    <p className="mt-1 text-xs text-destructive">{registerForm.errors.password}</p>
                  )}
                </div>

                <div>
                  <label className="block text-sm font-body font-medium text-foreground mb-1.5">{t("login.confirmPassword")}</label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <input 
                      type={showConfirmPassword ? "text" : "password"} 
                      placeholder="••••••••" 
                      value={registerForm.data.password_confirmation}
                      onChange={(e) => registerForm.setData('password_confirmation', e.target.value)}
                      className="w-full pl-10 pr-10 py-2.5 rounded-lg bg-background border border-border text-foreground placeholder:text-muted-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" 
                    />
                    <button 
                      type="button" 
                      onClick={() => setShowConfirmPassword(!showConfirmPassword)} 
                      className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                    >
                      {showConfirmPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                    </button>
                  </div>
                </div>

                <button 
                  type="submit" 
                  disabled={registerForm.processing}
                  className="w-full py-3 bg-primary text-primary-foreground rounded-lg font-body text-sm font-medium hover:opacity-90 transition-opacity disabled:opacity-50"
                >
                  {registerForm.processing ? 'Creating account...' : t("login.createAccount")}
                </button>
              </form>
            )}

            <p className="mt-6 text-center text-sm font-body text-muted-foreground">
              {isLogin ? (
                <>
                  {t("login.dontHaveAccount")}{' '}
                  <button type="button" onClick={() => setIsLogin(false)} className="text-primary hover:underline font-medium">
                    {t("login.register")}
                  </button>
                </>
              ) : (
                <>
                  {t("login.alreadyHaveAccount")}{' '}
                  <button type="button" onClick={() => setIsLogin(true)} className="text-primary hover:underline font-medium">
                    {t("login.signIn")}
                  </button>
                </>
              )}
            </p>
          </div>
        </motion.div>
      </div>
    </MainLayout>
  );
};

export default Login;


import React from 'react';
import { useState } from "react";
import { Head, Link, router } from "@inertiajs/react";
import { ChevronRight, Lock, Eye, EyeOff, Save } from "lucide-react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";
import MainLayout from "@/Components/layout/MainLayout";
import axios from "axios";

const ChangePassword = () => {
  const { t } = useTranslation();
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [showPasswords, setShowPasswords] = useState({
    current: false,
    new: false,
    confirm: false,
  });
  const [form, setForm] = useState({
    current_password: '',
    password: '',
    password_confirmation: '',
  });

  const updateField = (field: string, value: string) => {
    setForm(prev => ({ ...prev, [field]: value }));
  };

  const togglePassword = (field: 'current' | 'new' | 'confirm') => {
    setShowPasswords(prev => ({ ...prev, [field]: !prev[field] }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (form.password !== form.password_confirmation) {
      toast.error('Passwords do not match');
      return;
    }

    if (form.password.length < 6) {
      toast.error('Password must be at least 6 characters');
      return;
    }

    setIsSubmitting(true);

    try {
      const response = await axios.post('/api/account/password-update', form);
      if (response.data.success) {
        toast.success('Password updated successfully!');
        router.visit('/account');
      } else {
        toast.error(response.data.message || 'Failed to update password');
      }
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Failed to update password');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <MainLayout>
      <Head title="Change Password - Khadyobitan" />
      <div className="section-padding bg-muted min-h-[80vh]">
        <div className="container-custom max-w-xl">
          {/* Breadcrumb */}
          <nav className="flex items-center gap-2 text-sm font-body text-muted-foreground mb-6">
            <Link href="/" className="hover:text-primary">{t("common.home")}</Link>
            <ChevronRight className="h-3 w-3" />
            <Link href="/account" className="hover:text-primary">{t("account.myAccount")}</Link>
            <ChevronRight className="h-3 w-3" />
            <span className="text-foreground">{t("account.changePassword")}</span>
          </nav>

          {/* Form Card */}
          <div className="bg-card border border-border rounded-xl p-6 sm:p-8">
            <div className="flex items-center gap-3 mb-6">
              <div className="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <Lock className="h-6 w-6 text-primary" />
              </div>
              <div>
                <h1 className="font-heading text-xl font-bold text-foreground">{t("account.changePassword")}</h1>
                <p className="font-body text-sm text-muted-foreground">{t("account.updateYourPassword")}</p>
              </div>
            </div>

            <form onSubmit={handleSubmit} className="space-y-5">
              <div>
                <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                  {t("account.currentPassword")} *
                </label>
                <div className="relative">
                  <input
                    type={showPasswords.current ? 'text' : 'password'}
                    value={form.current_password}
                    onChange={(e) => updateField('current_password', e.target.value)}
                    required
                    className="w-full px-4 py-3 pr-12 rounded-lg bg-background border border-border text-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    onClick={() => togglePassword('current')}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                  >
                    {showPasswords.current ? <EyeOff className="h-5 w-5" /> : <Eye className="h-5 w-5" />}
                  </button>
                </div>
              </div>

              <div>
                <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                  {t("account.newPassword")} *
                </label>
                <div className="relative">
                  <input
                    type={showPasswords.new ? 'text' : 'password'}
                    value={form.password}
                    onChange={(e) => updateField('password', e.target.value)}
                    required
                    minLength={6}
                    className="w-full px-4 py-3 pr-12 rounded-lg bg-background border border-border text-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    onClick={() => togglePassword('new')}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                  >
                    {showPasswords.new ? <EyeOff className="h-5 w-5" /> : <Eye className="h-5 w-5" />}
                  </button>
                </div>
                <p className="font-body text-xs text-muted-foreground mt-1.5">
                  {t("account.passwordMinLength") || "Minimum 6 characters"}
                </p>
              </div>

              <div>
                <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                  {t("account.confirmNewPassword")} *
                </label>
                <div className="relative">
                  <input
                    type={showPasswords.confirm ? 'text' : 'password'}
                    value={form.password_confirmation}
                    onChange={(e) => updateField('password_confirmation', e.target.value)}
                    required
                    minLength={6}
                    className="w-full px-4 py-3 pr-12 rounded-lg bg-background border border-border text-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30"
                    placeholder="••••••••"
                  />
                  <button
                    type="button"
                    onClick={() => togglePassword('confirm')}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                  >
                    {showPasswords.confirm ? <EyeOff className="h-5 w-5" /> : <Eye className="h-5 w-5" />}
                  </button>
                </div>
              </div>

              <div className="flex items-center gap-4 pt-4">
                <button
                  type="submit"
                  disabled={isSubmitting}
                  className="flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-lg text-sm font-body font-medium hover:opacity-90 transition-opacity disabled:opacity-50"
                >
                  <Save className="h-4 w-4" />
                  {isSubmitting ? 'Updating...' : t("account.updatePassword")}
                </button>
                <Link
                  href="/account"
                  className="px-6 py-3 border border-border rounded-lg text-sm font-body font-medium text-foreground hover:bg-muted transition-colors"
                >
                  {t("common.cancel")}
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </MainLayout>
  );
};

export default ChangePassword;

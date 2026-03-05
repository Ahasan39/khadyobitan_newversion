import React from 'react';
import { useState } from "react";
import { Head, Link, router } from "@inertiajs/react";
import { ChevronRight, User, Save } from "lucide-react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";
import MainLayout from "@/Components/layout/MainLayout";
import axios from "axios";

interface Customer {
  id: number;
  name: string;
  email?: string;
  phone: string;
  address?: string;
}

interface ProfileEditProps {
  customer: Customer;
}

const ProfileEdit = ({ customer }: ProfileEditProps) => {
  const { t } = useTranslation();
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [form, setForm] = useState({
    name: customer?.name || '',
    email: customer?.email || '',
    phone: customer?.phone || '',
    address: customer?.address || '',
  });

  const updateField = (field: string, value: string) => {
    setForm(prev => ({ ...prev, [field]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);

    try {
      const response = await axios.post('/api/account/profile-update', form);
      if (response.data.success) {
        toast.success('Profile updated successfully!');
        router.visit('/account');
      } else {
        toast.error(response.data.message || 'Failed to update profile');
      }
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Failed to update profile');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <MainLayout>
      <Head title="Edit Profile - Khadyobitan" />
      <div className="section-padding bg-muted min-h-[80vh]">
        <div className="container-custom max-w-2xl">
          {/* Breadcrumb */}
          <nav className="flex items-center gap-2 text-sm font-body text-muted-foreground mb-6">
            <Link href="/" className="hover:text-primary">{t("common.home")}</Link>
            <ChevronRight className="h-3 w-3" />
            <Link href="/account" className="hover:text-primary">{t("account.myAccount")}</Link>
            <ChevronRight className="h-3 w-3" />
            <span className="text-foreground">{t("account.editProfile")}</span>
          </nav>

          {/* Form Card */}
          <div className="bg-card border border-border rounded-xl p-6 sm:p-8">
            <div className="flex items-center gap-3 mb-6">
              <div className="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <User className="h-6 w-6 text-primary" />
              </div>
              <div>
                <h1 className="font-heading text-xl font-bold text-foreground">{t("account.editProfile")}</h1>
                <p className="font-body text-sm text-muted-foreground">{t("account.updateYourInfo")}</p>
              </div>
            </div>

            <form onSubmit={handleSubmit} className="space-y-5">
              <div>
                <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                  {t("checkout.fullName")} *
                </label>
                <input
                  type="text"
                  value={form.name}
                  onChange={(e) => updateField('name', e.target.value)}
                  required
                  className="w-full px-4 py-3 rounded-lg bg-background border border-border text-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30"
                  placeholder={t("checkout.yourFullName")}
                />
              </div>

              <div>
                <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                  {t("checkout.email")}
                </label>
                <input
                  type="email"
                  value={form.email}
                  onChange={(e) => updateField('email', e.target.value)}
                  className="w-full px-4 py-3 rounded-lg bg-background border border-border text-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30"
                  placeholder="email@example.com"
                />
              </div>

              <div>
                <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                  {t("account.phone")} *
                </label>
                <input
                  type="tel"
                  value={form.phone}
                  onChange={(e) => updateField('phone', e.target.value)}
                  required
                  className="w-full px-4 py-3 rounded-lg bg-background border border-border text-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30"
                  placeholder="01XXXXXXXXX"
                />
              </div>

              <div>
                <label className="block text-sm font-body font-medium text-foreground mb-1.5">
                  {t("checkout.fullAddress")}
                </label>
                <textarea
                  value={form.address}
                  onChange={(e) => updateField('address', e.target.value)}
                  rows={3}
                  className="w-full px-4 py-3 rounded-lg bg-background border border-border text-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none"
                  placeholder={t("checkout.houseRoadBlock")}
                />
              </div>

              <div className="flex items-center gap-4 pt-4">
                <button
                  type="submit"
                  disabled={isSubmitting}
                  className="flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-lg text-sm font-body font-medium hover:opacity-90 transition-opacity disabled:opacity-50"
                >
                  <Save className="h-4 w-4" />
                  {isSubmitting ? 'Saving...' : t("account.saveChanges")}
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

export default ProfileEdit;

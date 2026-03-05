import React from 'react';
import { useState } from "react";
import { Head, Link, useForm, router } from "@inertiajs/react";
import { motion } from "framer-motion";
import { User, Package, Heart, MapPin, Settings, LogOut, ChevronRight, Star, Lock, Edit } from "lucide-react";
import { useTranslation } from "react-i18next";
import MainLayout from "@/Components/layout/MainLayout";

interface Customer {
  id: number;
  name: string;
  email?: string;
  phone: string;
  address?: string;
}

interface Order {
  id: number;
  order_id: string;
  total: number;
  status: string;
  created_at: string;
  order_details?: any[];
}

interface AccountProps {
  customer: Customer;
  orders: {
    data: Order[];
    total: number;
  };
}

const statusColors: Record<string, string> = {
  delivered: "bg-primary/10 text-primary",
  shipped: "bg-accent/20 text-accent-foreground",
  processing: "bg-muted text-muted-foreground",
  pending: "bg-yellow-100 text-yellow-700",
  cancelled: "bg-destructive/10 text-destructive",
};

const formatDate = (dateString: string) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const Account = ({ customer, orders }: AccountProps) => {
  const { t } = useTranslation();
  const [activeTab, setActiveTab] = useState("overview");
  
  // Ensure orders data is properly formatted
  const ordersList = orders?.data || [];

  const handleLogout = () => {
    router.post('/logout');
  };

  const tabs = [
    { id: "overview", label: t("account.overview"), icon: User },
    { id: "orders", label: t("account.orders"), icon: Package },
    { id: "addresses", label: t("account.addresses"), icon: MapPin },
    { id: "settings", label: t("account.settings"), icon: Settings },
  ];

  return (
    <MainLayout>
      <Head title="My Account - Khadyobitan" />
      <div className="section-padding bg-muted min-h-[80vh]">
      <div className="container-custom">
        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
          <h1 className="font-heading text-3xl font-bold text-foreground mb-8">{t("account.myAccount")}</h1>

          <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div className="bg-card border border-border rounded-xl p-4">
              <div className="text-center mb-6 p-4">
                <div className="w-16 h-16 rounded-full bg-primary/10 text-primary mx-auto flex items-center justify-center mb-3"><User className="h-7 w-7" /></div>
                <h3 className="font-heading text-base font-semibold text-foreground">{customer?.name || t("account.guestUser")}</h3>
                <p className="font-body text-xs text-muted-foreground">{customer?.email || customer?.phone}</p>
              </div>
              <nav className="space-y-1">
                {tabs.map((tab) => (
                  <button key={tab.id} onClick={() => setActiveTab(tab.id)} className={`w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-body font-medium transition-colors ${activeTab === tab.id ? "bg-primary/10 text-primary" : "text-muted-foreground hover:bg-muted"}`}>
                    <tab.icon className="h-4 w-4" />{tab.label}
                  </button>
                ))}
                <button onClick={handleLogout} className="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-body font-medium text-destructive hover:bg-destructive/10 transition-colors">
                  <LogOut className="h-4 w-4" />{t("account.signOut")}
                </button>
              </nav>
            </div>

            <div className="lg:col-span-3">
              {activeTab === "overview" && (
                <div className="space-y-6">
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {[
                      { label: t("account.totalOrders"), value: orders?.total?.toString() || "0", icon: Package },
                      { label: t("account.wishlistItems"), value: "0", icon: Heart },
                      { label: t("account.rewardPoints"), value: "0", icon: Star },
                      { label: t("account.savedAddresses"), value: customer?.address ? "1" : "0", icon: MapPin },
                    ].map((stat) => (
                      <div key={stat.label} className="bg-card border border-border rounded-xl p-4 text-center">
                        <stat.icon className="h-5 w-5 text-primary mx-auto mb-2" />
                        <p className="font-heading text-xl font-bold text-foreground">{stat.value}</p>
                        <p className="font-body text-xs text-muted-foreground">{stat.label}</p>
                      </div>
                    ))}
                  </div>

                  <div className="bg-card border border-border rounded-xl p-6">
                    <div className="flex items-center justify-between mb-4">
                      <h3 className="font-heading text-lg font-semibold text-foreground">{t("account.recentOrders")}</h3>
                      <Link href="/account/orders" className="text-sm font-body text-primary hover:underline flex items-center gap-1">{t("account.viewAll")} <ChevronRight className="h-3 w-3" /></Link>
                    </div>
                    <div className="space-y-3">
                      {ordersList.length === 0 ? (
                        <p className="text-center text-muted-foreground py-4">{t("account.noOrders") || "No orders yet"}</p>
                      ) : (
                        ordersList.slice(0, 3).map((order) => (
                          <Link key={order.id} href={`/account/orders/${order.id}`} className="flex items-center justify-between p-3 bg-muted rounded-lg hover:bg-muted/70 transition-colors">
                            <div>
                              <p className="font-body text-sm font-semibold text-foreground">{order.order_id}</p>
                              <p className="font-body text-xs text-muted-foreground">{formatDate(order.created_at)}{order.order_details?.length ? ` · ${order.order_details.length} items` : ''}</p>
                            </div>
                            <div className="flex items-center gap-3">
                              <span className={`px-2 py-1 rounded-full text-xs font-body font-medium capitalize ${statusColors[order.status?.toLowerCase()] || statusColors.processing}`}>{order.status}</span>
                              <span className="font-body text-sm font-semibold text-foreground">৳{order.total}</span>
                            </div>
                          </Link>
                        ))
                      )}
                    </div>
                  </div>
                </div>
              )}

              {activeTab === "orders" && (
                <div className="bg-card border border-border rounded-xl p-6">
                  <h3 className="font-heading text-lg font-semibold text-foreground mb-4">{t("account.orderHistory")}</h3>
                  <div className="space-y-3">
                    {ordersList.length === 0 ? (
                      <p className="text-center text-muted-foreground py-8">{t("account.noOrders") || "No orders yet"}</p>
                    ) : (
                      ordersList.map((order) => (
                        <Link key={order.id} href={`/account/orders/${order.id}`} className="flex items-center justify-between p-4 border border-border rounded-lg hover:bg-muted transition-colors">
                          <div>
                            <p className="font-body text-sm font-semibold text-foreground">{order.order_id}</p>
                            <p className="font-body text-xs text-muted-foreground">{formatDate(order.created_at)}{order.order_details?.length ? ` · ${order.order_details.length} items` : ''}</p>
                          </div>
                          <div className="flex items-center gap-4">
                            <span className={`px-2 py-1 rounded-full text-xs font-body font-medium capitalize ${statusColors[order.status?.toLowerCase()] || statusColors.processing}`}>{order.status}</span>
                            <span className="font-body text-sm font-semibold text-foreground">৳{order.total}</span>
                            <ChevronRight className="h-4 w-4 text-muted-foreground" />
                          </div>
                        </Link>
                      ))
                    )}
                  </div>
                </div>
              )}

              {activeTab === "addresses" && (
                <div className="bg-card border border-border rounded-xl p-6">
                  <div className="flex items-center justify-between mb-4">
                    <h3 className="font-heading text-lg font-semibold text-foreground">{t("account.savedAddresses")}</h3>
                  </div>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {customer?.address ? (
                      <div className="p-4 border rounded-lg border-primary bg-primary/5">
                        <div className="flex items-center gap-2 mb-2">
                          <MapPin className="h-4 w-4 text-primary" />
                          <span className="font-body text-sm font-semibold text-foreground">{t("account.primaryAddress") || "Primary"}</span>
                          <span className="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-xs font-body">{t("account.default")}</span>
                        </div>
                        <p className="font-body text-sm text-muted-foreground mb-1">{customer.address}</p>
                        <p className="font-body text-xs text-muted-foreground">{customer.phone}</p>
                      </div>
                    ) : (
                      <p className="text-center text-muted-foreground py-8 col-span-2">{t("account.noAddresses") || "No saved addresses"}</p>
                    )}
                  </div>
                </div>
              )}

              {activeTab === "settings" && (
                <div className="space-y-4">
                  <div className="bg-card border border-border rounded-xl p-6">
                    <h3 className="font-heading text-lg font-semibold text-foreground mb-2">{t("account.profileSettings")}</h3>
                    <p className="font-body text-sm text-muted-foreground mb-5">{t("account.manageYourAccount") || "Manage your account details and security settings."}</p>
                    <div className="space-y-3">
                      <Link href="/account/profile-edit" className="flex items-center justify-between p-4 border border-border rounded-xl hover:bg-muted transition-colors group">
                        <div className="flex items-center gap-3">
                          <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <Edit className="h-5 w-5 text-primary" />
                          </div>
                          <div>
                            <p className="font-body text-sm font-semibold text-foreground">{t("account.editProfile") || "Edit Profile"}</p>
                            <p className="font-body text-xs text-muted-foreground">{customer?.name} · {customer?.phone}</p>
                          </div>
                        </div>
                        <ChevronRight className="h-4 w-4 text-muted-foreground group-hover:text-primary transition-colors" />
                      </Link>
                      <Link href="/account/change-password" className="flex items-center justify-between p-4 border border-border rounded-xl hover:bg-muted transition-colors group">
                        <div className="flex items-center gap-3">
                          <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <Lock className="h-5 w-5 text-primary" />
                          </div>
                          <div>
                            <p className="font-body text-sm font-semibold text-foreground">{t("account.changePassword") || "Change Password"}</p>
                            <p className="font-body text-xs text-muted-foreground">{t("account.keepAccountSecure") || "Keep your account secure"}</p>
                          </div>
                        </div>
                        <ChevronRight className="h-4 w-4 text-muted-foreground group-hover:text-primary transition-colors" />
                      </Link>
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>
        </motion.div>
      </div>
    </div>
    </MainLayout>
  );
};
export default Account;


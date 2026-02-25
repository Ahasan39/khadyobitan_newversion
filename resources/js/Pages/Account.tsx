import { useState } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { User, Package, Heart, MapPin, Settings, LogOut, ChevronRight, Star } from "lucide-react";
import { useTranslation } from "react-i18next";

const mockOrders = [
  { id: "NP-10234", date: "Feb 8, 2026", status: "Delivered", total: 1280, items: 3 },
  { id: "NP-10198", date: "Jan 22, 2026", status: "Shipped", total: 890, items: 2 },
  { id: "NP-10145", date: "Jan 10, 2026", status: "Delivered", total: 2150, items: 5 },
];

const statusColors: Record<string, string> = {
  Delivered: "bg-primary/10 text-primary",
  Shipped: "bg-accent/20 text-accent-foreground",
  Processing: "bg-muted text-muted-foreground",
};

const Account = () => {
  const { t } = useTranslation();
  const [activeTab, setActiveTab] = useState("overview");

  const tabs = [
    { id: "overview", label: t("account.overview"), icon: User },
    { id: "orders", label: t("account.orders"), icon: Package },
    { id: "addresses", label: t("account.addresses"), icon: MapPin },
    { id: "settings", label: t("account.settings"), icon: Settings },
  ];

  return (
    <div className="section-padding bg-muted min-h-[80vh]">
      <div className="container-custom">
        <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
          <h1 className="font-heading text-3xl font-bold text-foreground mb-8">{t("account.myAccount")}</h1>

          <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div className="bg-card border border-border rounded-xl p-4">
              <div className="text-center mb-6 p-4">
                <div className="w-16 h-16 rounded-full bg-primary/10 text-primary mx-auto flex items-center justify-center mb-3"><User className="h-7 w-7" /></div>
                <h3 className="font-heading text-base font-semibold text-foreground">{t("account.guestUser")}</h3>
                <p className="font-body text-xs text-muted-foreground">guest@khadyobitan.com</p>
              </div>
              <nav className="space-y-1">
                {tabs.map((tab) => (
                  <button key={tab.id} onClick={() => setActiveTab(tab.id)} className={`w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-body font-medium transition-colors ${activeTab === tab.id ? "bg-primary/10 text-primary" : "text-muted-foreground hover:bg-muted"}`}>
                    <tab.icon className="h-4 w-4" />{tab.label}
                  </button>
                ))}
                <Link to="/login" className="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-body font-medium text-destructive hover:bg-destructive/10 transition-colors">
                  <LogOut className="h-4 w-4" />{t("account.signOut")}
                </Link>
              </nav>
            </div>

            <div className="lg:col-span-3">
              {activeTab === "overview" && (
                <div className="space-y-6">
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {[
                      { label: t("account.totalOrders"), value: "12", icon: Package },
                      { label: t("account.wishlistItems"), value: "5", icon: Heart },
                      { label: t("account.rewardPoints"), value: "340", icon: Star },
                      { label: t("account.savedAddresses"), value: "2", icon: MapPin },
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
                      <button onClick={() => setActiveTab("orders")} className="text-sm font-body text-primary hover:underline flex items-center gap-1">{t("account.viewAll")} <ChevronRight className="h-3 w-3" /></button>
                    </div>
                    <div className="space-y-3">
                      {mockOrders.map((order) => (
                        <div key={order.id} className="flex items-center justify-between p-3 bg-muted rounded-lg">
                          <div>
                            <p className="font-body text-sm font-semibold text-foreground">{order.id}</p>
                            <p className="font-body text-xs text-muted-foreground">{order.date} · {order.items} items</p>
                          </div>
                          <div className="flex items-center gap-3">
                            <span className={`px-2 py-1 rounded-full text-xs font-body font-medium ${statusColors[order.status]}`}>{order.status}</span>
                            <span className="font-body text-sm font-semibold text-foreground">৳{order.total}</span>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
              )}

              {activeTab === "orders" && (
                <div className="bg-card border border-border rounded-xl p-6">
                  <h3 className="font-heading text-lg font-semibold text-foreground mb-4">{t("account.orderHistory")}</h3>
                  <div className="space-y-3">
                    {mockOrders.map((order) => (
                      <div key={order.id} className="flex items-center justify-between p-4 border border-border rounded-lg hover:bg-muted transition-colors">
                        <div>
                          <p className="font-body text-sm font-semibold text-foreground">{order.id}</p>
                          <p className="font-body text-xs text-muted-foreground">{order.date} · {order.items} items</p>
                        </div>
                        <div className="flex items-center gap-4">
                          <span className={`px-2 py-1 rounded-full text-xs font-body font-medium ${statusColors[order.status]}`}>{order.status}</span>
                          <span className="font-body text-sm font-semibold text-foreground">৳{order.total}</span>
                          <button className="text-xs font-body text-primary hover:underline">{t("account.details")}</button>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {activeTab === "addresses" && (
                <div className="bg-card border border-border rounded-xl p-6">
                  <div className="flex items-center justify-between mb-4">
                    <h3 className="font-heading text-lg font-semibold text-foreground">{t("account.savedAddresses")}</h3>
                    <button className="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-body font-medium hover:opacity-90 transition-opacity">{t("account.addNew")}</button>
                  </div>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {[
                      { label: "Home", address: "House 12, Road 5, Dhanmondi, Dhaka 1205", phone: "+880 1712-345678", isDefault: true },
                      { label: "Office", address: "Floor 8, Tower B, Gulshan Avenue, Dhaka 1212", phone: "+880 1898-765432", isDefault: false },
                    ].map((addr) => (
                      <div key={addr.label} className={`p-4 border rounded-lg ${addr.isDefault ? "border-primary bg-primary/5" : "border-border"}`}>
                        <div className="flex items-center gap-2 mb-2">
                          <MapPin className="h-4 w-4 text-primary" />
                          <span className="font-body text-sm font-semibold text-foreground">{addr.label}</span>
                          {addr.isDefault && <span className="px-2 py-0.5 bg-primary/10 text-primary rounded-full text-xs font-body">{t("account.default")}</span>}
                        </div>
                        <p className="font-body text-sm text-muted-foreground mb-1">{addr.address}</p>
                        <p className="font-body text-xs text-muted-foreground">{addr.phone}</p>
                        <div className="mt-3 flex gap-2">
                          <button className="text-xs font-body text-primary hover:underline">{t("account.edit")}</button>
                          <button className="text-xs font-body text-destructive hover:underline">{t("account.delete")}</button>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              )}

              {activeTab === "settings" && (
                <div className="bg-card border border-border rounded-xl p-6">
                  <h3 className="font-heading text-lg font-semibold text-foreground mb-6">{t("account.profileSettings")}</h3>
                  <form className="space-y-4 max-w-lg" onSubmit={(e) => e.preventDefault()}>
                    {[
                      { label: t("checkout.fullName"), value: "Guest User", type: "text" },
                      { label: t("checkout.email"), value: "guest@khadyobitan.com", type: "email" },
                      { label: t("account.phone"), value: "+880 1712-345678", type: "tel" },
                    ].map((field) => (
                      <div key={field.label}>
                        <label className="block text-sm font-body font-medium text-foreground mb-1.5">{field.label}</label>
                        <input type={field.type} defaultValue={field.value} className="w-full px-4 py-2.5 rounded-lg bg-background border border-border text-foreground text-sm font-body focus:outline-none focus:ring-2 focus:ring-primary/30" />
                      </div>
                    ))}
                    <button className="px-6 py-2.5 bg-primary text-primary-foreground rounded-lg text-sm font-body font-medium hover:opacity-90 transition-opacity">{t("account.saveChanges")}</button>
                  </form>
                </div>
              )}
            </div>
          </div>
        </motion.div>
      </div>
    </div>
  );
};

export default Account;

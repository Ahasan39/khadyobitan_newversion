import React from 'react';
import { Head, Link } from "@inertiajs/react";
import { ChevronRight, Package, ChevronLeft } from "lucide-react";
import { useTranslation } from "react-i18next";
import MainLayout from "@/Components/layout/MainLayout";

interface Order {
  id: number;
  order_id: string;
  total: number;
  status: string;
  created_at: string;
  order_details?: any[];
}

interface OrdersProps {
  orders: {
    data: Order[];
    total: number;
    current_page: number;
    last_page: number;
  };
}

const statusColors: Record<string, string> = {
  delivered: "bg-green-100 text-green-700",
  shipped: "bg-blue-100 text-blue-700",
  processing: "bg-yellow-100 text-yellow-700",
  pending: "bg-orange-100 text-orange-700",
  cancelled: "bg-red-100 text-red-700",
};

const formatDate = (dateString: string) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const Orders = ({ orders }: OrdersProps) => {
  const { t } = useTranslation();
  const ordersList = orders?.data || [];

  return (
    <MainLayout>
      <Head title="My Orders - Khadyobitan" />
      <div className="section-padding bg-muted min-h-[80vh]">
        <div className="container-custom max-w-4xl">
          {/* Breadcrumb */}
          <nav className="flex items-center gap-2 text-sm font-body text-muted-foreground mb-6">
            <Link href="/" className="hover:text-primary">{t("common.home")}</Link>
            <ChevronRight className="h-3 w-3" />
            <Link href="/account" className="hover:text-primary">{t("account.myAccount")}</Link>
            <ChevronRight className="h-3 w-3" />
            <span className="text-foreground">{t("account.orders")}</span>
          </nav>

          {/* Header */}
          <div className="flex items-center justify-between mb-6">
            <h1 className="font-heading text-2xl font-bold text-foreground">{t("account.orderHistory")}</h1>
            <Link href="/account" className="flex items-center gap-1 text-sm font-body text-primary hover:underline">
              <ChevronLeft className="h-4 w-4" />
              {t("account.backToAccount")}
            </Link>
          </div>

          {/* Orders List */}
          <div className="bg-card border border-border rounded-xl">
            {ordersList.length === 0 ? (
              <div className="p-12 text-center">
                <Package className="h-12 w-12 text-muted-foreground mx-auto mb-4" />
                <h3 className="font-heading text-lg font-semibold text-foreground mb-2">
                  {t("account.noOrdersTitle") || "No orders yet"}
                </h3>
                <p className="font-body text-sm text-muted-foreground mb-6">
                  {t("account.noOrdersDesc") || "When you place orders, they will appear here."}
                </p>
                <Link 
                  href="/shop" 
                  className="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity"
                >
                  {t("checkout.goToShop")}
                </Link>
              </div>
            ) : (
              <div className="divide-y divide-border">
                {ordersList.map((order) => (
                  <div key={order.id} className="p-4 sm:p-6 hover:bg-muted/50 transition-colors">
                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                      <div className="flex-1">
                        <div className="flex items-center gap-3 mb-2">
                          <p className="font-heading text-base font-semibold text-foreground">
                            {order.order_id}
                          </p>
                          <span className={`px-2.5 py-1 rounded-full text-xs font-body font-medium capitalize ${statusColors[order.status?.toLowerCase()] || statusColors.pending}`}>
                            {order.status}
                          </span>
                        </div>
                        <p className="font-body text-sm text-muted-foreground">
                          {formatDate(order.created_at)} · {order.order_details?.length || 0} {t("account.items")}
                        </p>
                      </div>
                      <div className="flex items-center gap-4">
                        <p className="font-heading text-lg font-bold text-foreground">
                          ৳{order.total}
                        </p>
                        <Link 
                          href={`/account/orders/${order.id}`}
                          className="px-4 py-2 border border-border rounded-lg text-sm font-body font-medium text-foreground hover:bg-muted transition-colors"
                        >
                          {t("account.viewDetails")}
                        </Link>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>

          {/* Pagination */}
          {orders.last_page > 1 && (
            <div className="flex items-center justify-center gap-2 mt-6">
              {orders.current_page > 1 && (
                <Link 
                  href={`/account/orders?page=${orders.current_page - 1}`}
                  className="px-4 py-2 border border-border rounded-lg text-sm font-body text-foreground hover:bg-muted transition-colors"
                >
                  {t("common.previous")}
                </Link>
              )}
              <span className="font-body text-sm text-muted-foreground px-4">
                {t("common.page")} {orders.current_page} {t("common.of")} {orders.last_page}
              </span>
              {orders.current_page < orders.last_page && (
                <Link 
                  href={`/account/orders?page=${orders.current_page + 1}`}
                  className="px-4 py-2 border border-border rounded-lg text-sm font-body text-foreground hover:bg-muted transition-colors"
                >
                  {t("common.next")}
                </Link>
              )}
            </div>
          )}
        </div>
      </div>
    </MainLayout>
  );
};

export default Orders;

import React from 'react';
import { Head, Link } from "@inertiajs/react";
import { ChevronRight, Package, MapPin, CreditCard, Clock, CheckCircle } from "lucide-react";
import { useTranslation } from "react-i18next";
import MainLayout from "@/Components/layout/MainLayout";

interface OrderItem {
  name: string;
  price: number;
  quantity: number;
}

interface Order {
  id: number;
  order_id: string;
  total: number;
  status: string;
  shipping_charge: number;
  discount: number;
  created_at: string;
  items: OrderItem[];
  shipping?: {
    name: string;
    phone: string;
    address: string;
  };
  payment?: {
    method: string;
    status: string;
  };
}

interface OrderDetailProps {
  order: Order;
}

const statusColors: Record<string, string> = {
  delivered: "bg-green-100 text-green-700",
  shipped: "bg-blue-100 text-blue-700",
  processing: "bg-yellow-100 text-yellow-700",
  pending: "bg-orange-100 text-orange-700",
  cancelled: "bg-red-100 text-red-700",
};

const statusIcons: Record<string, React.ElementType> = {
  delivered: CheckCircle,
  shipped: Package,
  processing: Clock,
  pending: Clock,
  cancelled: Package,
};

const formatDate = (dateString: string) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    weekday: 'long',
    month: 'long', 
    day: 'numeric', 
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const paymentMethodLabels: Record<string, string> = {
  cod: 'Cash on Delivery',
  bkash: 'bKash',
  nagad: 'Nagad',
  card: 'Credit/Debit Card',
  bank: 'Bank Transfer',
};

const OrderDetail = ({ order }: OrderDetailProps) => {
  const { t } = useTranslation();
  const StatusIcon = statusIcons[order.status?.toLowerCase()] || Clock;
  
  const subtotal = order.items?.reduce((sum, item) => sum + (item.price * item.quantity), 0) || 0;

  return (
    <MainLayout>
      <Head title={`Order ${order.order_id} - Khadyobitan`} />
      <div className="section-padding bg-muted min-h-[80vh]">
        <div className="container-custom max-w-4xl">
          {/* Breadcrumb */}
          <nav className="flex items-center gap-2 text-sm font-body text-muted-foreground mb-6">
            <Link href="/" className="hover:text-primary">{t("common.home")}</Link>
            <ChevronRight className="h-3 w-3" />
            <Link href="/account" className="hover:text-primary">{t("account.myAccount")}</Link>
            <ChevronRight className="h-3 w-3" />
            <Link href="/account/orders" className="hover:text-primary">{t("account.orders")}</Link>
            <ChevronRight className="h-3 w-3" />
            <span className="text-foreground">{order.order_id}</span>
          </nav>

          {/* Order Header */}
          <div className="bg-card border border-border rounded-xl p-6 mb-6">
            <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
              <div>
                <h1 className="font-heading text-2xl font-bold text-foreground mb-1">
                  {t("account.order")} #{order.order_id}
                </h1>
                <p className="font-body text-sm text-muted-foreground">
                  {t("account.placedOn")} {formatDate(order.created_at)}
                </p>
              </div>
              <div className={`inline-flex items-center gap-2 px-4 py-2 rounded-full ${statusColors[order.status?.toLowerCase()] || statusColors.pending}`}>
                <StatusIcon className="h-4 w-4" />
                <span className="font-body text-sm font-semibold capitalize">{order.status}</span>
              </div>
            </div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {/* Order Items */}
            <div className="lg:col-span-2 space-y-6">
              <div className="bg-card border border-border rounded-xl p-6">
                <h2 className="font-heading text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                  <Package className="h-5 w-5 text-primary" />
                  {t("account.orderItems")}
                </h2>
                <div className="space-y-4">
                  {order.items?.map((item, index) => (
                    <div key={index} className="flex items-center justify-between py-3 border-b border-border last:border-0">
                      <div className="flex-1">
                        <p className="font-body text-sm font-medium text-foreground">{item.name}</p>
                        <p className="font-body text-xs text-muted-foreground">
                          ৳{item.price} × {item.quantity}
                        </p>
                      </div>
                      <p className="font-body text-sm font-semibold text-foreground">
                        ৳{item.price * item.quantity}
                      </p>
                    </div>
                  ))}
                </div>
              </div>

              {/* Shipping Address */}
              {order.shipping && (
                <div className="bg-card border border-border rounded-xl p-6">
                  <h2 className="font-heading text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                    <MapPin className="h-5 w-5 text-primary" />
                    {t("account.shippingAddress")}
                  </h2>
                  <div className="font-body text-sm text-muted-foreground space-y-1">
                    <p className="font-medium text-foreground">{order.shipping.name}</p>
                    <p>{order.shipping.phone}</p>
                    <p>{order.shipping.address}</p>
                  </div>
                </div>
              )}
            </div>

            {/* Order Summary Sidebar */}
            <div className="space-y-6">
              {/* Payment Info */}
              <div className="bg-card border border-border rounded-xl p-6">
                <h2 className="font-heading text-lg font-semibold text-foreground mb-4 flex items-center gap-2">
                  <CreditCard className="h-5 w-5 text-primary" />
                  {t("account.paymentInfo")}
                </h2>
                <div className="font-body text-sm space-y-2">
                  <div className="flex justify-between">
                    <span className="text-muted-foreground">{t("account.method")}</span>
                    <span className="font-medium text-foreground">
                      {paymentMethodLabels[order.payment?.method || 'cod']}
                    </span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-muted-foreground">{t("account.status")}</span>
                    <span className={`font-medium capitalize ${order.payment?.status === 'paid' ? 'text-green-600' : 'text-orange-600'}`}>
                      {order.payment?.status || 'Pending'}
                    </span>
                  </div>
                </div>
              </div>

              {/* Order Summary */}
              <div className="bg-card border border-border rounded-xl p-6">
                <h2 className="font-heading text-lg font-semibold text-foreground mb-4">
                  {t("cart.orderSummary")}
                </h2>
                <div className="font-body text-sm space-y-3">
                  <div className="flex justify-between">
                    <span className="text-muted-foreground">{t("cart.subtotal")}</span>
                    <span className="text-foreground">৳{subtotal}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-muted-foreground">{t("cart.shipping")}</span>
                    <span className={order.shipping_charge === 0 ? "text-green-600 font-medium" : "text-foreground"}>
                      {order.shipping_charge === 0 ? t("cart.free") : `৳${order.shipping_charge}`}
                    </span>
                  </div>
                  {order.discount > 0 && (
                    <div className="flex justify-between">
                      <span className="text-muted-foreground">{t("cart.discount")}</span>
                      <span className="text-green-600">-৳{order.discount}</span>
                    </div>
                  )}
                  <div className="border-t border-border pt-3 flex justify-between">
                    <span className="font-heading font-bold text-foreground">{t("cart.total")}</span>
                    <span className="font-heading text-lg font-bold text-primary">৳{order.total}</span>
                  </div>
                </div>
              </div>

              {/* Actions */}
              <div className="space-y-2">
                <Link 
                  href="/account/orders" 
                  className="block w-full text-center px-4 py-3 bg-primary text-primary-foreground rounded-lg font-body font-medium text-sm hover:opacity-90 transition-opacity"
                >
                  {t("account.backToOrders")}
                </Link>
                <Link 
                  href="/contact" 
                  className="block w-full text-center px-4 py-3 border border-border rounded-lg font-body font-medium text-sm text-foreground hover:bg-muted transition-colors"
                >
                  {t("account.needHelp")}
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </MainLayout>
  );
};

export default OrderDetail;

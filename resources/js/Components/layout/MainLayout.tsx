import React from "react";
import Header from "./Header";
import Footer from "./Footer";
import MobileBottomNav from "./MobileBottomNav";
import BackToTop from "../BackToTop";
import PromoPopup from "../PromoPopup";
import WhatsAppButton from "../WhatsAppButton";
import ScrollToTop from "../ScrollToTop";

const MainLayout = ({ children }: { children: React.ReactNode }) => {
    return (
        <div className="min-h-screen bg-background">
            <ScrollToTop />
            <Header />
            <main className="pb-16 lg:pb-0">{children}</main>
            <Footer />
            <MobileBottomNav />
            <BackToTop />
            <PromoPopup />
            <WhatsAppButton />
        </div>
    );
};

export default MainLayout;

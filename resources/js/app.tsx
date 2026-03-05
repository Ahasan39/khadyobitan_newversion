import './bootstrap';
import '../css/app.css';

import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { TooltipProvider } from '@/Components/ui/tooltip';
import { Toaster } from '@/Components/ui/toaster';
import { Toaster as Sonner } from '@/Components/ui/sonner';
import '@/i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Khadyobitan';
const queryClient = new QueryClient();

class ErrorBoundary extends React.Component<{ children: React.ReactNode }, { hasError: boolean }> {
    constructor(props: any) {
        super(props);
        this.state = { hasError: false };
    }
    static getDerivedStateFromError() { return { hasError: true }; }
    componentDidCatch(error: any, errorInfo: any) { console.error("React Error:", error, errorInfo); }
    render() {
        if (this.state.hasError) return <div className="p-8 text-center"><h1 className="text-xl font-bold">Something went wrong.</h1><button onClick={() => window.location.reload()} className="mt-4 px-4 py-2 bg-primary text-white rounded">Reload Page</button></div>;
        return this.props.children;
    }
}

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <QueryClientProvider client={queryClient}>
                <TooltipProvider>
                    <ErrorBoundary>
                        <App {...props} />
                    </ErrorBoundary>
                    <Toaster />
                    <Sonner />
                </TooltipProvider>
            </QueryClientProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});

@echo off
echo ========================================
echo Installing Frontend Dependencies
echo ========================================
echo.

echo Installing core dependencies...
call npm install @hookform/resolvers class-variance-authority clsx cmdk date-fns embla-carousel-react framer-motion i18next i18next-browser-languagedetector input-otp lucide-react next-themes react-day-picker react-hook-form react-i18next react-resizable-panels recharts sonner tailwind-merge tailwindcss-animate vaul zod zustand

echo.
echo Installing Radix UI components...
call npm install @radix-ui/react-accordion @radix-ui/react-alert-dialog @radix-ui/react-aspect-ratio @radix-ui/react-avatar @radix-ui/react-checkbox @radix-ui/react-collapsible @radix-ui/react-context-menu @radix-ui/react-dialog @radix-ui/react-dropdown-menu @radix-ui/react-hover-card @radix-ui/react-label @radix-ui/react-menubar @radix-ui/react-navigation-menu @radix-ui/react-popover @radix-ui/react-progress @radix-ui/react-radio-group @radix-ui/react-scroll-area @radix-ui/react-select @radix-ui/react-separator @radix-ui/react-slider @radix-ui/react-slot @radix-ui/react-switch @radix-ui/react-tabs @radix-ui/react-toast @radix-ui/react-toggle @radix-ui/react-toggle-group @radix-ui/react-tooltip

echo.
echo Installing TanStack Query...
call npm install @tanstack/react-query

echo.
echo Installing Tailwind CSS plugins...
call npm install -D @tailwindcss/typography

echo.
echo ========================================
echo Dependencies installed successfully!
echo ========================================
pause

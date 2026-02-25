@echo off
echo ========================================
echo Frontend Integration Setup Script
echo ========================================
echo.

echo Step 1: Installing Inertia.js for Laravel...
call composer require inertiajs/inertia-laravel
echo.

echo Step 2: Publishing Inertia middleware...
call php artisan inertia:middleware
echo.

echo Step 3: Copying frontend files...
xcopy /E /I /Y "khadyobitan_frontend\src" "resources\js"
copy /Y "khadyobitan_frontend\tailwind.config.ts" "tailwind.config.ts"
copy /Y "khadyobitan_frontend\postcss.config.js" "postcss.config.js"
copy /Y "khadyobitan_frontend\tsconfig.json" "tsconfig.json"
echo.

echo Step 4: Installing frontend dependencies...
call npm install @inertiajs/react
call npm install
echo.

echo Step 5: Building assets...
call npm run build
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Review FRONTEND_INTEGRATION_GUIDE.md
echo 2. Update routes in routes/web.php
echo 3. Create frontend controllers
echo 4. Run: php artisan serve
echo 5. Run: npm run dev (in another terminal)
echo.
pause

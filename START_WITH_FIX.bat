@echo off
echo ========================================
echo   ASSET FIX - STARTING SERVER
echo ========================================
echo.

echo Step 1: Clearing all caches...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear
echo Done!
echo.

echo Step 2: Checking if files exist...
if exist "public\frontEnd\css\bootstrap.min.css" (
    echo [OK] CSS files exist
) else (
    echo [ERROR] CSS files NOT FOUND!
    pause
    exit
)

if exist "public\frontEnd\js\jquery-3.7.1.min.js" (
    echo [OK] JS files exist
) else (
    echo [ERROR] JS files NOT FOUND!
    pause
    exit
)
echo.

echo Step 3: Starting Laravel server WITH CUSTOM ROUTER...
echo.
echo ========================================
echo   SERVER STARTING
echo ========================================
echo.
echo IMPORTANT: Server will now start with server.php router
echo This allows /public/ prefix URLs to work correctly
echo.
echo After server starts, open your browser and go to:
echo.
echo   http://127.0.0.1:8000/test-direct.php
echo.
echo This will test if assets are loading correctly.
echo.
echo If the test page shows green checkmarks, then go to:
echo   http://127.0.0.1:8000
echo.
echo Press Ctrl+C to stop the server
echo ========================================
echo.

REM Use PHP built-in server with server.php router
REM WITHOUT -t public flag, so server.php can properly handle /public/ stripping
call php -S 127.0.0.1:8000 server.php

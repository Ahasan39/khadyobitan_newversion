@echo off
echo ========================================
echo Step 2: Copying Frontend Files
echo ========================================
echo.

echo Copying React components...
xcopy /E /I /Y "khadyobitan_frontend\src\components" "resources\js\components"
xcopy /E /I /Y "khadyobitan_frontend\src\pages" "resources\js\Pages"
xcopy /E /I /Y "khadyobitan_frontend\src\hooks" "resources\js\hooks"
xcopy /E /I /Y "khadyobitan_frontend\src\lib" "resources\js\lib"
xcopy /E /I /Y "khadyobitan_frontend\src\store" "resources\js\store"
xcopy /E /I /Y "khadyobitan_frontend\src\i18n" "resources\js\i18n"
xcopy /E /I /Y "khadyobitan_frontend\src\data" "resources\js\data"
xcopy /E /I /Y "khadyobitan_frontend\src\assets" "resources\js\assets"

echo.
echo Copying configuration files...
copy /Y "khadyobitan_frontend\tailwind.config.ts" "tailwind.config.ts"
copy /Y "khadyobitan_frontend\postcss.config.js" "postcss.config.js"
copy /Y "khadyobitan_frontend\tsconfig.json" "tsconfig.json"
copy /Y "khadyobitan_frontend\components.json" "components.json"

echo.
echo ========================================
echo Files copied successfully!
echo ========================================
pause

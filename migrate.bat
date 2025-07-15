@echo off
cd /d "c:\laragon\www\pizza-app"
php artisan migrate --force
pause

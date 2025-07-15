@echo off
echo Ejecutando migraciones pendientes...
cd /d "c:\laragon\www\pizza-app"

echo.
echo === Ejecutando migración de campos de pago ===
php artisan migrate --path=database/migrations/2025_07_15_131000_add_payment_fields_to_orders_table.php --force

echo.
echo === Ejecutando migración de estado 'cart' ===
php artisan migrate --path=database/migrations/2025_07_15_174700_add_cart_status_to_orders_table.php --force

echo.
echo === Ejecutando todas las migraciones pendientes ===
php artisan migrate --force

echo.
echo ¡Migraciones completadas!
pause

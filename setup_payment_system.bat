@echo off
echo ==========================================
echo       CONFIGURACIÓN SISTEMA DE PAGOS
echo ==========================================
echo.

cd /d "c:\laragon\www\pizza-app"

echo Ejecutando comando personalizado de configuración...
php artisan pizza:setup-payment

echo.
echo ==========================================
echo         CONFIGURACIÓN COMPLETADA
echo ==========================================
echo.
echo ✅ El sistema de pagos está listo para usar
echo 💳 Modal de pago integrado con el flujo
echo 🧾 Generación automática de tickets
echo 🔄 Flujo completo: Pago → Cocina → Mesero
echo.
pause

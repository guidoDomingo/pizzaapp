# 🔧 Resolución de Error: number_format() con String

## ❌ **Error Identificado:**
```
number_format(): Argument #1 ($num) must be of type int|float, string given
```

## 🔍 **Causa del Problema:**
1. Los valores de la base de datos pueden llegar como strings en algunos casos
2. El método `formatTotal()` intentaba usar `number_format()` directamente sin validar el tipo
3. Se estaba pasando texto (método de pago) a `formatTotal()` que espera números

## ✅ **Soluciones Implementadas:**

### 1. **Conversión Segura en formatTotal():**
```php
protected function formatTotal($label, $amount, $bold = false)
{
    $currency = $this->config['ticket']['currency'];
    // Convertir a float para asegurar que number_format funcione correctamente
    $numericAmount = is_numeric($amount) ? (float) $amount : 0.0;
    $formattedAmount = $currency . number_format($numericAmount, 2);
    
    // ...resto del código
}
```

### 2. **Conversión Segura en formatItem():**
```php
protected function formatItem($item)
{
    $name = substr($item->product->name, 0, 20);
    // Convertir a float para asegurar formato correcto
    $unitPrice = is_numeric($item->unit_price) ? (float) $item->unit_price : 0.0;
    $totalPrice = is_numeric($item->total_price) ? (float) $item->total_price : 0.0;
    $quantity = is_numeric($item->quantity) ? (int) $item->quantity : 0;
    
    return sprintf("%-20s %3d %6.2f %8.2f", 
        $name, $quantity, $unitPrice, $totalPrice
    );
}
```

### 3. **Nuevo Método formatText() para Texto:**
```php
protected function formatText($label, $text)
{
    return sprintf("%30s %12s", $label, $text);
}
```

### 4. **Uso Correcto según Tipo de Dato:**
```php
// Para números (totales, montos)
$lines[] = $this->formatTotal('Pagado:', $order->amount_paid);

// Para texto (método de pago)
$lines[] = $this->formatText('Método:', $this->getPaymentMethodName($order->payment_method ?? 'cash'));
```

## 🛡️ **Protecciones Agregadas:**
- ✅ Validación `is_numeric()` antes de conversiones
- ✅ Valores por defecto (0.0, 0) para casos de error
- ✅ Separación entre formateo de números y texto
- ✅ Conversión explícita de tipos `(float)` y `(int)`

## ✅ **Resultado:**
El sistema de tickets ahora maneja correctamente:
- ✅ Valores numéricos como string desde la DB
- ✅ Valores null o inválidos
- ✅ Texto y números por separado
- ✅ Generación de tickets sin errores de formato

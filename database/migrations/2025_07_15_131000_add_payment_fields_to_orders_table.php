<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Campos adicionales para el proceso de pago
            $table->decimal('amount_paid', 10, 2)->default(0)->after('total');
            $table->decimal('change_amount', 10, 2)->default(0)->after('amount_paid');
            $table->timestamp('paid_at')->nullable()->after('change_amount');
            $table->string('payment_reference')->nullable()->after('paid_at');
            $table->text('payment_notes')->nullable()->after('payment_reference');
            $table->string('ticket_number')->nullable()->after('payment_notes');
            $table->boolean('ticket_printed')->default(false)->after('ticket_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'amount_paid',
                'change_amount', 
                'paid_at',
                'payment_reference',
                'payment_notes',
                'ticket_number',
                'ticket_printed'
            ]);
        });
    }
};

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
        Schema::create('cash_extractions', function (Blueprint $table) {
            $table->id();

            // Usuario que hizo el corte (opcional)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Contexto
            $table->unsignedTinyInteger('turno');          // 1 ó 2
            $table->date('operation_date');                // fecha operativa del turno

            // Archivo
            $table->string('image_path');                  // storage/app/... o public/...
            $table->string('image_original_name')->nullable();

            // Detalle ventas por método de pago
            $table->decimal('cash_sales', 12, 2)->default(0);
            $table->decimal('debit_card_sales', 12, 2)->default(0);
            $table->decimal('credit_card_sales', 12, 2)->default(0);
            $table->decimal('credit_sales', 12, 2)->default(0);
            $table->decimal('total_sales_payment_methods', 12, 2)->default(0);

            // Detalle propinas por método de pago
            $table->decimal('cash_tips', 12, 2)->default(0);
            $table->decimal('debit_card_tips', 12, 2)->default(0);
            $table->decimal('credit_card_tips', 12, 2)->default(0);
            $table->decimal('total_tips_payment_methods', 12, 2)->default(0);

            // Resumen que usas en la UI
            $table->decimal('monto_debito', 12, 2)->default(0);
            $table->decimal('monto_credito', 12, 2)->default(0);
            $table->decimal('efectivo', 12, 2)->default(0);

            // Info de la extracción
            $table->uuid('run_id')->nullable();
            $table->uuid('extraction_agent_id')->nullable();
            $table->json('extraction_metadata')->nullable();

            // Estado del corte
            $table->string('status', 30)->default('procesado'); // procesado | validado | rechazado | etc.
            $table->string('error_message')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_extractions');
    }
};

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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Unidad de negocio (ej. Restaurante, Bar, Delivery…)
            $table->string('business_unit', 150);

            // Nombre del gasto (ej. Luz, Renta, Compra de insumos…)
            $table->string('expense_name', 150);

            // Nombre del proveedor (ej. CFE, Coca-Cola…)
            $table->string('provider_name', 150);

            // Activo / Inactivo
            $table->boolean('is_active')->default(true);

            // Si en algún momento quieres auditar quién creó la categoría:
            // $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

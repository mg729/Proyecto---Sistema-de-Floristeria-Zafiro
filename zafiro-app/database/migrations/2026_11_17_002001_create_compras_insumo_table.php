<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras_insumo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->restrictOnDelete();
            $table->foreignId('proveedor_id')->constrained('proveedores')->restrictOnDelete();
            $table->foreignId('empleado_id')->constrained('empleados')->restrictOnDelete();
            $table->string('numero_documento', 30)->nullable();
            $table->date('fecha_compra');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('isv', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('estado', ['borrador', 'recibida', 'anulada'])->default('borrador');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique(['proveedor_id', 'numero_documento']);
            $table->index(['tienda_id', 'fecha_compra']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras_insumo');
    }
};

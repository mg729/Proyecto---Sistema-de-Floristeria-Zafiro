<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago')->restrictOnDelete();
            $table->decimal('monto', 12, 2);
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'reembolsado'])->default('pendiente');
            $table->string('referencia', 60)->nullable();
            $table->char('tarjeta_ultimos4', 4)->nullable();
            $table->string('tarjeta_marca', 20)->nullable();
            $table->string('comprobante', 255)->nullable();
            $table->dateTime('fecha_pago')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE pagos ADD CONSTRAINT chk_pagos_1 CHECK (monto > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

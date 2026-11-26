<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('autorizacion_cai_id')->constrained('autorizaciones_cai')->restrictOnDelete();
            $table->foreignId('pedido_id')->unique()->constrained('pedidos')->restrictOnDelete();
            $table->char('numero_factura', 19)->unique();
            $table->unsignedInteger('correlativo');
            $table->dateTime('fecha_emision');
            $table->string('cliente_nombre', 150);
            $table->char('cliente_rtn', 14)->nullable();
            $table->decimal('importe_exento', 12, 2)->default(0);
            $table->decimal('importe_gravado_15', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('isv_15', 12, 2);
            $table->decimal('total', 12, 2);
            $table->string('total_en_letras', 255);
            $table->enum('estado', ['emitida', 'anulada'])->default('emitida');
            $table->string('motivo_anulacion', 255)->nullable();
            $table->foreignId('anulada_por')->nullable()->constrained('empleados')->nullOnDelete();
            $table->dateTime('fecha_anulacion')->nullable();
            $table->timestamps();

            $table->unique(['autorizacion_cai_id', 'correlativo']);
            $table->index('fecha_emision');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};

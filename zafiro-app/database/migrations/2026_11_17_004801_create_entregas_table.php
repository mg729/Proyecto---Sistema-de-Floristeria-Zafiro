<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->unique()->constrained('pedidos')->cascadeOnDelete();
            $table->enum('tipo', ['domicilio', 'retiro_tienda'])->default('domicilio');
            $table->foreignId('zona_id')->nullable()->constrained('zonas')->restrictOnDelete();
            $table->foreignId('turno_entrega_id')->constrained('turnos_entrega')->restrictOnDelete();
            $table->date('fecha_entrega');
            $table->string('nombre_destinatario', 120);
            $table->string('telefono_destinatario', 20);
            $table->string('direccion_entrega', 255)->nullable();
            $table->string('referencias', 255)->nullable();
            $table->boolean('es_sorpresa')->default(false);
            $table->foreignId('repartidor_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->dateTime('fecha_hora_salida')->nullable();
            $table->dateTime('fecha_hora_entrega')->nullable();
            $table->string('recibido_por', 120)->nullable();
            $table->string('foto_evidencia', 255)->nullable();
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();

            $table->index(['fecha_entrega', 'turno_entrega_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};

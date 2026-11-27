<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('zona_id')->constrained('zonas')->restrictOnDelete();
            $table->string('alias', 50);
            $table->string('nombre_contacto', 120)->nullable();
            $table->string('telefono_contacto', 20)->nullable();
            $table->string('direccion_exacta', 255);
            $table->string('referencias', 255)->nullable();
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};

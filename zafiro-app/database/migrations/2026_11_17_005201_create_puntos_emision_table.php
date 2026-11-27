<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puntos_emision', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->cascadeOnDelete();
            $table->char('codigo', 3);
            $table->string('descripcion', 100);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['tienda_id', 'codigo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puntos_emision');
    }
};

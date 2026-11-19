<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fechas_especiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('ocasion_id')->nullable()->constrained('ocasiones')->nullOnDelete();
            $table->string('titulo', 120);
            $table->string('nombre_persona', 120);
            $table->date('fecha');
            $table->boolean('es_anual')->default(true);
            $table->unsignedTinyInteger('dias_anticipacion')->default(7);
            $table->string('notas', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['cliente_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fechas_especiales');
    }
};
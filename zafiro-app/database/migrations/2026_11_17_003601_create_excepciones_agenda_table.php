<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excepciones_agenda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->cascadeOnDelete();
            $table->foreignId('turno_entrega_id')->constrained('turnos_entrega')->cascadeOnDelete();
            $table->date('fecha');
            $table->unsignedSmallInteger('cupo_maximo');
            $table->string('motivo', 150);
            $table->timestamps();

            $table->unique(['tienda_id', 'turno_entrega_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excepciones_agenda');
    }
};

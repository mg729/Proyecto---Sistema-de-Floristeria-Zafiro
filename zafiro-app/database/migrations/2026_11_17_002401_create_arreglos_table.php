<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arreglos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formato_arreglo_id')->constrained('formatos_arreglo')->restrictOnDelete();
            $table->string('nombre', 120);
            $table->string('slug', 140)->unique();
            $table->text('descripcion');
            $table->text('descripcion_botanica')->nullable();
            $table->text('cuidados')->nullable();
            $table->boolean('destacado')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('destacado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arreglos');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arreglo_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arreglo_id')->constrained('arreglos')->cascadeOnDelete();
            $table->string('ruta', 255);
            $table->string('texto_alternativo', 150)->nullable();
            $table->unsignedTinyInteger('orden')->default(0);
            $table->timestamps();

            $table->index(['arreglo_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arreglo_imagenes');
    }
};
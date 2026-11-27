<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_flor', function (Blueprint $table) {
            $table->foreignId('insumo_id')->primary()->constrained('insumos')->cascadeOnDelete();
            $table->string('nombre_cientifico', 120)->nullable();
            $table->string('origen', 60)->nullable();
            $table->unsignedTinyInteger('vida_florero_dias')->nullable();
            $table->string('temporada', 60)->nullable();
            $table->text('cuidados')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_flor');
    }
};

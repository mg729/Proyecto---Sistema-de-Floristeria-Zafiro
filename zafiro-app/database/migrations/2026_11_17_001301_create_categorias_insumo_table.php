<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_insumo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 60)->unique();
            $table->string('slug', 70)->unique();
            $table->unsignedTinyInteger('paso_creador')->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->unsignedTinyInteger('orden')->default(0);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE categorias_insumo ADD CONSTRAINT chk_categorias_insumo_1 CHECK (paso_creador BETWEEN 1 AND 4)');
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias_insumo');
    }
};

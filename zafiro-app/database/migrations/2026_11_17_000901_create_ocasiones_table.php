<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocasiones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80)->unique();
            $table->string('slug', 90)->unique();
            $table->text('descripcion')->nullable();
            $table->string('imagen_banner', 255)->nullable();
            $table->char('color_hex', 7)->nullable();
            $table->boolean('es_temporada')->default(false);
            $table->unsignedTinyInteger('mes_inicio')->nullable();
            $table->unsignedTinyInteger('mes_fin')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE ocasiones ADD CONSTRAINT chk_ocasiones_1 CHECK (mes_inicio BETWEEN 1 AND 12)');
        DB::statement('ALTER TABLE ocasiones ADD CONSTRAINT chk_ocasiones_2 CHECK (mes_fin BETWEEN 1 AND 12)');
    }

    public function down(): void
    {
        Schema::dropIfExists('ocasiones');
    }
};
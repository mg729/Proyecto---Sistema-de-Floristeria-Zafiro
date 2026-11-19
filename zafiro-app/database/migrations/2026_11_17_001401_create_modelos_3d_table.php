<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modelos_3d', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->enum('tipo_geometria', ['procedural', 'glb'])->default('procedural');
            $table->string('clave_procedural', 50)->nullable();
            $table->string('archivo', 255)->nullable();
            $table->decimal('escala', 5, 2)->default(1);
            $table->decimal('altura_cm', 6, 2)->nullable();
            $table->string('miniatura', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE modelos_3d ADD CONSTRAINT chk_modelos_3d_1 CHECK ((tipo_geometria=\'procedural\' AND clave_procedural IS NOT NULL) OR (tipo_geometria=\'glb\' AND archivo IS NOT NULL))');
    }

    public function down(): void
    {
        Schema::dropIfExists('modelos_3d');
    }
};
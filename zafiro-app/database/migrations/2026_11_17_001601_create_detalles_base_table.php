<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_base', function (Blueprint $table) {
            $table->foreignId('insumo_id')->primary()->constrained('insumos')->cascadeOnDelete();
            $table->foreignId('formato_arreglo_id')->constrained('formatos_arreglo')->restrictOnDelete();
            $table->string('material', 60);
            $table->unsignedSmallInteger('capacidad_min_tallos')->default(1);
            $table->unsignedSmallInteger('capacidad_max_tallos');
            $table->decimal('diametro_apertura_cm', 6, 2);
            $table->decimal('altura_cm', 6, 2);
            $table->decimal('cargo_armado', 10, 2)->default(0);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE detalles_base ADD CONSTRAINT chk_detalles_base_1 CHECK (capacidad_max_tallos >= capacidad_min_tallos)');
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_base');
    }
};

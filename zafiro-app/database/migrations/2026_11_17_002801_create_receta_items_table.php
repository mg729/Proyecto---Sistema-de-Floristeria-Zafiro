<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receta_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variante_arreglo_id')->constrained('variantes_arreglo')->cascadeOnDelete();
            $table->foreignId('insumo_id')->constrained('insumos')->restrictOnDelete();
            $table->decimal('cantidad', 10, 2);
            $table->timestamps();

            $table->unique(['variante_arreglo_id', 'insumo_id']);
        });

        DB::statement('ALTER TABLE receta_items ADD CONSTRAINT chk_receta_items_1 CHECK (cantidad > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('receta_items');
    }
};
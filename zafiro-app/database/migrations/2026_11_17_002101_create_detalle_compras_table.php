<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_insumo_id')->constrained('compras_insumo')->cascadeOnDelete();
            $table->foreignId('insumo_id')->constrained('insumos')->restrictOnDelete();
            $table->decimal('cantidad', 10, 2);
            $table->decimal('costo_unitario', 10, 2);
            $table->timestamps();

            $table->unique(['compra_insumo_id', 'insumo_id']);
        });

        DB::statement('ALTER TABLE detalle_compras ADD CONSTRAINT chk_detalle_compras_1 CHECK (cantidad > 0)');
        DB::statement('ALTER TABLE detalle_compras ADD CONSTRAINT chk_detalle_compras_2 CHECK (costo_unitario >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_compras');
    }
};

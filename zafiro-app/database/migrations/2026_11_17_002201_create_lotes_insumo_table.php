<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes_insumo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->restrictOnDelete();
            $table->foreignId('insumo_id')->constrained('insumos')->restrictOnDelete();
            $table->foreignId('detalle_compra_id')->nullable()->constrained('detalle_compras')->nullOnDelete();
            $table->string('codigo_lote', 30)->unique();
            $table->decimal('cantidad_inicial', 10, 2);
            $table->decimal('cantidad_disponible', 10, 2);
            $table->decimal('costo_unitario', 10, 2);
            $table->dateTime('fecha_ingreso');
            $table->date('fecha_vencimiento')->nullable();
            $table->timestamps();

            $table->index(['tienda_id', 'insumo_id', 'fecha_ingreso']);
            $table->index('fecha_vencimiento');
        });

        DB::statement('ALTER TABLE lotes_insumo ADD CONSTRAINT chk_lotes_insumo_1 CHECK (cantidad_inicial > 0)');
        DB::statement('ALTER TABLE lotes_insumo ADD CONSTRAINT chk_lotes_insumo_2 CHECK (cantidad_disponible BETWEEN 0 AND cantidad_inicial)');
        DB::statement('ALTER TABLE lotes_insumo ADD CONSTRAINT chk_lotes_insumo_3 CHECK (costo_unitario >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes_insumo');
    }
};

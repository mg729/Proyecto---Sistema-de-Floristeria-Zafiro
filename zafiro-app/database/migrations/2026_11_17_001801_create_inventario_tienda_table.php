<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_tienda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->cascadeOnDelete();
            $table->foreignId('insumo_id')->constrained('insumos')->restrictOnDelete();
            $table->decimal('stock_actual', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->string('ubicacion', 60)->nullable();
            $table->timestamps();

            $table->unique(['tienda_id', 'insumo_id']);
        });

        DB::statement('ALTER TABLE inventario_tienda ADD CONSTRAINT chk_inventario_tienda_1 CHECK (stock_actual >= 0)');
        DB::statement('ALTER TABLE inventario_tienda ADD CONSTRAINT chk_inventario_tienda_2 CHECK (stock_minimo >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_tienda');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_insumo_id')->constrained('lotes_insumo')->restrictOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->enum('tipo', ['entrada_compra', 'consumo_armado', 'merma', 'ajuste_positivo', 'ajuste_negativo']);
            $table->decimal('cantidad', 10, 2);
            $table->foreignId('pedido_item_id')->nullable()->constrained('pedido_items')->nullOnDelete();
            $table->foreignId('merma_id')->nullable()->constrained('mermas')->nullOnDelete();
            $table->string('motivo', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['lote_insumo_id', 'created_at']);
        });

        DB::statement('ALTER TABLE movimientos_inventario ADD CONSTRAINT chk_movimientos_inventario_1 CHECK (cantidad > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('tienda_id')->constrained('tiendas')->restrictOnDelete();
            $table->foreignId('estado_pedido_id')->constrained('estados_pedido')->restrictOnDelete();
            $table->foreignId('cupon_id')->nullable()->constrained('cupones')->nullOnDelete();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('descuento_cupon', 12, 2)->default(0);
            $table->decimal('costo_envio', 10, 2)->default(0);
            $table->decimal('isv', 12, 2);
            $table->decimal('total', 12, 2);
            $table->string('notas_cliente', 500)->nullable();
            $table->dateTime('fecha_limite_pago')->nullable();
            $table->string('motivo_cancelacion', 255)->nullable();
            $table->timestamps();

            $table->index(['tienda_id', 'estado_pedido_id']);
            $table->index('created_at');
        });

        DB::statement('ALTER TABLE pedidos ADD CONSTRAINT chk_pedidos_1 CHECK (total >= 0)');
        DB::statement('ALTER TABLE pedidos ADD CONSTRAINT chk_pedidos_2 CHECK (descuento_cupon >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};

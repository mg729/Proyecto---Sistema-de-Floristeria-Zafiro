<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('variante_arreglo_id')->nullable()->constrained('variantes_arreglo')->restrictOnDelete();
            $table->foreignId('diseno_personalizado_id')->nullable()->constrained('disenos_personalizados')->restrictOnDelete();
            $table->string('descripcion', 200);
            $table->unsignedSmallInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->foreignId('promocion_id')->nullable()->constrained('promociones')->nullOnDelete();
            $table->decimal('descuento', 10, 2)->default(0);
            $table->foreignId('florista_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->enum('estado_preparacion', ['pendiente', 'en_armado', 'listo'])->default('pendiente');
            $table->dateTime('fecha_inicio_armado')->nullable();
            $table->dateTime('fecha_fin_armado')->nullable();
            $table->foreignId('plantilla_tarjeta_id')->nullable()->constrained('plantillas_tarjeta')->nullOnDelete();
            $table->string('dedicatoria_para', 100)->nullable();
            $table->string('dedicatoria_mensaje', 500)->nullable();
            $table->string('dedicatoria_firma', 100)->nullable();
            $table->timestamps();

            $table->index(['florista_id', 'estado_preparacion']);
        });

        DB::statement('ALTER TABLE pedido_items ADD CONSTRAINT chk_pedido_items_1 CHECK ((variante_arreglo_id IS NULL) <> (diseno_personalizado_id IS NULL))');
        DB::statement('ALTER TABLE pedido_items ADD CONSTRAINT chk_pedido_items_2 CHECK (cantidad > 0)');
        DB::statement('ALTER TABLE pedido_items ADD CONSTRAINT chk_pedido_items_3 CHECK (descuento >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
    }
};

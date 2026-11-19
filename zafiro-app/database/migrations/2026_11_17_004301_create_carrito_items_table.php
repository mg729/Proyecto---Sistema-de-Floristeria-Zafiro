<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrito_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrito_id')->constrained('carritos')->cascadeOnDelete();
            $table->foreignId('variante_arreglo_id')->nullable()->constrained('variantes_arreglo')->restrictOnDelete();
            $table->foreignId('diseno_personalizado_id')->nullable()->constrained('disenos_personalizados')->restrictOnDelete();
            $table->unsignedSmallInteger('cantidad')->default(1);
            $table->foreignId('plantilla_tarjeta_id')->nullable()->constrained('plantillas_tarjeta')->nullOnDelete();
            $table->string('dedicatoria_para', 100)->nullable();
            $table->string('dedicatoria_mensaje', 500)->nullable();
            $table->string('dedicatoria_firma', 100)->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE carrito_items ADD CONSTRAINT chk_carrito_items_1 CHECK ((variante_arreglo_id IS NULL) <> (diseno_personalizado_id IS NULL))');
        DB::statement('ALTER TABLE carrito_items ADD CONSTRAINT chk_carrito_items_2 CHECK (cantidad > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('carrito_items');
    }
};

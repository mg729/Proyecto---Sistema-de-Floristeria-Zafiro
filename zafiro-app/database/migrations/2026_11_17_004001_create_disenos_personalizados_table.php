<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disenos_personalizados', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('tienda_id')->constrained('tiendas')->restrictOnDelete();
            $table->foreignId('base_insumo_id')->constrained('insumos')->restrictOnDelete();
            $table->foreignId('ocasion_id')->nullable()->constrained('ocasiones')->nullOnDelete();
            $table->string('nombre', 100)->default('Mi arreglo');
            $table->json('configuracion_3d')->nullable();
            $table->string('imagen_previa', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disenos_personalizados');
    }
};
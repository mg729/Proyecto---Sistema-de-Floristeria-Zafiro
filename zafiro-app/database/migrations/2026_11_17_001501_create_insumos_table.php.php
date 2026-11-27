<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_insumo_id')->constrained('categorias_insumo')->restrictOnDelete();
            $table->foreignId('unidad_medida_id')->constrained('unidades_medida')->restrictOnDelete();
            $table->foreignId('modelo_3d_id')->nullable()->constrained('modelos_3d')->nullOnDelete();
            $table->string('sku', 30)->unique();
            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->string('color_nombre', 40)->nullable();
            $table->char('color_hex', 7)->nullable();
            $table->decimal('precio_componente', 10, 2);
            $table->string('imagen', 255)->nullable();
            $table->boolean('visible_en_creador')->default(true);
            $table->unsignedSmallInteger('max_por_diseno')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['visible_en_creador', 'activo']);
        });

        DB::statement('ALTER TABLE insumos ADD CONSTRAINT chk_insumos_1 CHECK (precio_componente >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};

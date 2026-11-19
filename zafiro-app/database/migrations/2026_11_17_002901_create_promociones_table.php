<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->nullable()->constrained('tiendas')->cascadeOnDelete();
            $table->foreignId('ocasion_id')->nullable()->constrained('ocasiones')->nullOnDelete();
            $table->string('nombre', 120);
            $table->string('descripcion', 255)->nullable();
            $table->enum('tipo_descuento', ['porcentaje', 'monto_fijo']);
            $table->decimal('valor', 10, 2);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['activo', 'fecha_inicio', 'fecha_fin']);
        });

        DB::statement('ALTER TABLE promociones ADD CONSTRAINT chk_promociones_1 CHECK (fecha_fin > fecha_inicio)');
        DB::statement('ALTER TABLE promociones ADD CONSTRAINT chk_promociones_2 CHECK (valor > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cupones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->nullable()->constrained('tiendas')->cascadeOnDelete();
            $table->string('codigo', 30)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->enum('tipo_descuento', ['porcentaje', 'monto_fijo']);
            $table->decimal('valor', 10, 2);
            $table->decimal('monto_minimo', 10, 2)->default(0);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->unsignedInteger('usos_maximos')->nullable();
            $table->unsignedSmallInteger('usos_por_cliente')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE cupones ADD CONSTRAINT chk_cupones_1 CHECK (fecha_fin > fecha_inicio)');
        DB::statement('ALTER TABLE cupones ADD CONSTRAINT chk_cupones_2 CHECK (valor > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('cupones');
    }
};
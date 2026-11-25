<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->restrictOnDelete();
            $table->foreignId('municipio_id')->constrained('municipios')->restrictOnDelete();
            $table->string('codigo', 10)->unique();
            $table->string('nombre', 100);
            $table->char('codigo_establecimiento', 3);
            $table->string('direccion', 255);
            $table->string('telefono', 20);
            $table->string('correo', 150)->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->unsignedSmallInteger('horas_preparacion_min')->default(4);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['empresa_id', 'codigo_establecimiento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiendas');
    }
};

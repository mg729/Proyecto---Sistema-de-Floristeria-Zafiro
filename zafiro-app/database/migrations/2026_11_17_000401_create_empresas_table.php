<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 150);
            $table->string('nombre_comercial', 150);
            $table->char('rtn', 14)->unique();
            $table->string('correo', 150)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion_fiscal', 255);
            $table->string('logo', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};

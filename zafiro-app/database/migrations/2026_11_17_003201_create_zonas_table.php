<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zonas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipio_id')->constrained('municipios')->restrictOnDelete();
            $table->string('nombre', 100);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['municipio_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zonas');
    }
};

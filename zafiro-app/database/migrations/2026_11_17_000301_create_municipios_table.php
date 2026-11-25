<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departamento_id')->constrained('departamentos')->restrictOnDelete();
            $table->char('codigo', 4)->unique();
            $table->string('nombre', 80);
            $table->timestamps();

            $table->unique(['departamento_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};

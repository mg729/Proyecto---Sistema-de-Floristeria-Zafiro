<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arreglo_promocion', function (Blueprint $table) {
            $table->foreignId('arreglo_id')->constrained('arreglos')->cascadeOnDelete();
            $table->foreignId('promocion_id')->constrained('promociones')->cascadeOnDelete();

            $table->primary(['arreglo_id', 'promocion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arreglo_promocion');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arreglo_ocasion', function (Blueprint $table) {
            $table->foreignId('arreglo_id')->constrained('arreglos')->cascadeOnDelete();
            $table->foreignId('ocasion_id')->constrained('ocasiones')->cascadeOnDelete();

            $table->primary(['arreglo_id', 'ocasion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arreglo_ocasion');
    }
};
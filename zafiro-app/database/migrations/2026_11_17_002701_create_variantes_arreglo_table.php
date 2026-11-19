<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variantes_arreglo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arreglo_id')->constrained('arreglos')->cascadeOnDelete();
            $table->foreignId('tamano_id')->constrained('tamanos')->restrictOnDelete();
            $table->string('sku', 30)->unique();
            $table->decimal('precio', 10, 2);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['arreglo_id', 'tamano_id']);
        });

        DB::statement('ALTER TABLE variantes_arreglo ADD CONSTRAINT chk_variantes_arreglo_1 CHECK (precio >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('variantes_arreglo');
    }
};
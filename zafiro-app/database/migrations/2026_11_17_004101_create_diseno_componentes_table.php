<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diseno_componentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diseno_personalizado_id')->constrained('disenos_personalizados')->cascadeOnDelete();
            $table->foreignId('insumo_id')->constrained('insumos')->restrictOnDelete();
            $table->decimal('cantidad', 10, 2);
            $table->timestamps();

            $table->unique(['diseno_personalizado_id', 'insumo_id']);
        });

        DB::statement('ALTER TABLE diseno_componentes ADD CONSTRAINT chk_diseno_componentes_1 CHECK (cantidad > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('diseno_componentes');
    }
};
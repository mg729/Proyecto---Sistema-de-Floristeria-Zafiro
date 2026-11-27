<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mermas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_insumo_id')->constrained('lotes_insumo')->restrictOnDelete();
            $table->foreignId('empleado_id')->constrained('empleados')->restrictOnDelete();
            $table->decimal('cantidad', 10, 2);
            $table->enum('motivo', ['marchita', 'danada', 'vencida', 'sobrante_temporada', 'otro']);
            $table->string('observacion', 255)->nullable();
            $table->dateTime('fecha');
            $table->timestamps();

            $table->index('fecha');
        });

        DB::statement('ALTER TABLE mermas ADD CONSTRAINT chk_mermas_1 CHECK (cantidad > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('mermas');
    }
};
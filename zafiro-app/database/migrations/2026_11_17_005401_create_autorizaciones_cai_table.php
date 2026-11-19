<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autorizaciones_cai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('punto_emision_id')->constrained('puntos_emision')->restrictOnDelete();
            $table->foreignId('tipo_documento_fiscal_id')->constrained('tipos_documento_fiscal')->restrictOnDelete();
            $table->char('cai', 37)->unique();
            $table->unsignedInteger('rango_inicial');
            $table->unsignedInteger('rango_final');
            $table->unsignedInteger('ultimo_correlativo')->default(0);
            $table->date('fecha_autorizacion');
            $table->date('fecha_limite_emision');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE autorizaciones_cai ADD CONSTRAINT chk_autorizaciones_cai_1 CHECK (rango_final >= rango_inicial)');
        DB::statement('ALTER TABLE autorizaciones_cai ADD CONSTRAINT chk_autorizaciones_cai_2 CHECK (ultimo_correlativo <= rango_final)');
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizaciones_cai');
    }
};

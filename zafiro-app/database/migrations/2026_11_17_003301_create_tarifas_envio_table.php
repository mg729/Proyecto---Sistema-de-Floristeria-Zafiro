<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifas_envio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->cascadeOnDelete();
            $table->foreignId('zona_id')->constrained('zonas')->cascadeOnDelete();
            $table->decimal('costo', 10, 2);
            $table->unsignedSmallInteger('tiempo_estimado_min');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['tienda_id', 'zona_id']);
        });

        DB::statement('ALTER TABLE tarifas_envio ADD CONSTRAINT chk_tarifas_envio_1 CHECK (costo >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifas_envio');
    }
};

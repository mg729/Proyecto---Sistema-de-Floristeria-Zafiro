<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turnos_entrega', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 30)->unique();
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE turnos_entrega ADD CONSTRAINT chk_turnos_entrega_1 CHECK (hora_fin > hora_inicio)');
    }

    public function down(): void
    {
        Schema::dropIfExists('turnos_entrega');
    }
};

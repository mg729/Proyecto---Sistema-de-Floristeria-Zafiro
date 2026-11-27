<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estados_pedido', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 30)->unique();
            $table->string('slug', 30)->unique();
            $table->unsignedTinyInteger('orden');
            $table->char('color_hex', 7);
            $table->boolean('es_final')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estados_pedido');
    }
};

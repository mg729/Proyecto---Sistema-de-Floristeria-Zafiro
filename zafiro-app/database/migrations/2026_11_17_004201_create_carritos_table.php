<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('tienda_id')->constrained('tiendas')->restrictOnDelete();
            $table->foreignId('cupon_id')->nullable()->constrained('cupones')->nullOnDelete();
            $table->dateTime('expira_en');
            $table->timestamps();

            $table->unique(['cliente_id', 'tienda_id']);
            $table->index('expira_en');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carritos');
    }
};

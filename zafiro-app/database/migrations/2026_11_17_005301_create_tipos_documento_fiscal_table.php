<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_documento_fiscal', function (Blueprint $table) {
            $table->id();
            $table->char('codigo', 2)->unique();
            $table->string('nombre', 60);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_documento_fiscal');
    }
};

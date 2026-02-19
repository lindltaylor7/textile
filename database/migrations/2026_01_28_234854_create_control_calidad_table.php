<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('control_calidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            $table->foreignId('defecto_id')->nullable()->constrained('defectos');
            $table->foreignId('usuario_id')->constrained('users');
            $table->integer('cantidad_defectuosa')->default(0);
            $table->enum('resultado', ['LIBERADO', 'RETENIDO'])->default('LIBERADO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_calidad');
    }
};

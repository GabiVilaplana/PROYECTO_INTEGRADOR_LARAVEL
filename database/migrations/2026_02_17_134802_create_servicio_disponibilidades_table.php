<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servicio_disponibilidades', function (Blueprint $table) {
            $table->id('IDDisponibilidad');
            $table->foreignId('idServicio')->constrained('servicios', 'IDServicio')->onDelete('cascade');
            $table->tinyInteger('dia_semana'); // 0 (Domingo) a 6 (Sábado)
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_disponibilidades');
    }
};

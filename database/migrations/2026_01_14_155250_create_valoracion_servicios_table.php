<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('valoracion_servicios', function (Blueprint $table) {
            $table->id('IDValoracion');
            $table->foreignId('idServicio')->constrained('servicios','IDServicio')->onDelete('cascade');
            $table->foreignId('idUsuario')->constrained('usuarios','IDUsuario')->onDelete('cascade');
            $table->integer('Puntuacion'); // 1 a 5
            $table->text('Comentario')->nullable();
            $table->date('Fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valoracion_servicios');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('IDMensaje');
            $table->foreignId('idUsuario')->constrained('usuarios','IDUsuario')->onDelete('cascade');
            $table->string('Nombre');
            $table->string('Email');
            $table->string('Asunto');
            $table->text('Contenido');
            $table->string('Estado');
            $table->dateTime('FechaEnvio');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};

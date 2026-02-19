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
        Schema::create('tarjetas_guardadas', function (Blueprint $table) {
            $table->id('IDTarjeta');
            $table->foreignId('idUsuario')->constrained('usuarios', 'IDUsuario')->onDelete('cascade');
            $table->string('NombreTitular');
            $table->string('NumeroTarjeta'); // Guardaremos solo los últimos 4 dígitos o una versión enmascarada en la realidad, pero aquí guardaremos el "token" o número para propósitos educativos.
            $table->string('MesExpiracion', 2);
            $table->string('AnioExpiracion', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjetas_guardadas');
    }
};

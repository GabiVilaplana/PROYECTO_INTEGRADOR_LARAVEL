<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id(); // IDMensaje
            $table->foreignId('usuario_id')->constrained('usuarios'); // IDUsuario
            $table->string('nombre');
            $table->string('email');
            $table->string('asunto');
            $table->text('contenido');
            $table->string('estado')->default('pendiente');
            $table->date('fecha_envio');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mensajes');
    }
};

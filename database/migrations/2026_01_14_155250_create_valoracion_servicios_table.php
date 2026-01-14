<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('valoracion_servicios', function (Blueprint $table) {
            $table->id(); // IDValoracion
            $table->foreignId('servicio_id')->constrained('servicios');
            $table->foreignId('usuario_id')->constrained('usuarios'); // necesitas la tabla usuarios
            $table->tinyInteger('puntuacion'); // 1 a 5
            $table->text('comentario')->nullable();
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('valoracion_servicios');
    }
};

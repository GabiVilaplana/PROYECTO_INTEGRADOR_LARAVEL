<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id(); // IDReserva
            $table->foreignId('usuario_id')->constrained('usuarios'); // IDUsuario
            $table->date('fecha_reserva');
            $table->string('estado')->default('pendiente');
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reservas');
    }
};

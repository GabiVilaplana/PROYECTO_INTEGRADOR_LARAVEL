<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reserva_detalles', function (Blueprint $table) {
            $table->id(); // IDDetalle
            $table->foreignId('reserva_id')->constrained('reservas'); // IDReserva
            $table->foreignId('servicio_id')->constrained('servicios'); // IDServicio
            $table->decimal('precio', 10, 2);
            $table->date('fecha_servicio');
            $table->time('hora_servicio');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reserva_detalles');
    }
};

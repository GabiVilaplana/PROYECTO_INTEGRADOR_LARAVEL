<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reserva_detalles', function (Blueprint $table) {
            $table->id('IDDetalle');
            $table->foreignId('idReserva')->constrained('reservas','IDReserva')->onDelete('cascade');
            $table->foreignId('idServicio')->constrained('servicios','IDServicio')->onDelete('cascade');
            $table->decimal('Precio', 8, 2);
            $table->date('FechaServicio');
            $table->time('HoraServicio');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reserva_detalles');
    }
};

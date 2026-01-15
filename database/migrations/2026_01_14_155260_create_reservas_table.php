<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('IDReserva');
            $table->foreignId('idUsuario')->constrained('usuarios','IDUsuario')->onDelete('cascade');
            $table->dateTime('FechaReserva');
            $table->string('Estado');
            $table->decimal('Total', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};

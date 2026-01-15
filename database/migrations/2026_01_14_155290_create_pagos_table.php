<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('IDPago');
            $table->foreignId('idReserva')->constrained('reservas','IDReserva')->onDelete('cascade');
            $table->string('MetodoPago');
            $table->string('Estado');
            $table->decimal('Importe', 8, 2);
            $table->dateTime('FechaPago');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

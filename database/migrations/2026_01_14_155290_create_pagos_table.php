<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id(); // IDPago
            $table->foreignId('reserva_id')->constrained('reservas');
            $table->string('metodo_pago');
            $table->string('estado')->default('pendiente');
            $table->decimal('importe', 10, 2);
            $table->date('fecha_pago');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pagos');
    }
};

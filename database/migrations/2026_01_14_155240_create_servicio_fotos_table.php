<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('servicio_fotos', function (Blueprint $table) {
            $table->id(); // IDFoto
            $table->foreignId('servicio_id')->constrained('servicios'); // IDServicio
            $table->string('ruta_foto');
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('servicio_fotos');
    }
};

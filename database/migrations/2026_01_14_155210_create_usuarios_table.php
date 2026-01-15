<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('IDUsuario');
            $table->string('Nombre');
            $table->string('Apellidos')->nullable();
            $table->string('CorreoElectronico')->unique();
            $table->string('Password');
            $table->foreignId('idRol')->constrained('rols','IDRol')->onDelete('cascade');
            $table->boolean('Activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};

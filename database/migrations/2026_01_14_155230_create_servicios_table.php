<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id('IDServicio');
            $table->string('Nombre');
            $table->text('Descripcion')->nullable();
            $table->decimal('Precio', 8, 2);
            $table->integer('Duracion'); // en minutos
            $table->boolean('Activo')->default(true);
            $table->foreignId('idCategoria')->constrained('categorias','IDCategoria')->onDelete('cascade');
            $table->foreignId('idProveedor')->constrained('usuarios','IDUsuario')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};

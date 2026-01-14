<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id(); // IDServicio
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('duracion'); // minutos
            $table->boolean('activo')->default(true);
            $table->foreignId('categoria_id')->constrained('categorias'); // IDCategoria
            $table->foreignId('proveedor_id')->constrained('proveedors'); // IDProveedor
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('servicios');
    }
};

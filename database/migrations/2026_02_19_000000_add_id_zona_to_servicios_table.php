<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            // Check if column exists before adding to avoid error if it does
            if (!Schema::hasColumn('servicios', 'idZona')) {
                $table->foreignId('idZona')->nullable()->constrained('zonas', 'id')->onDelete('set null');
            }
            if (!Schema::hasColumn('servicios', 'Direccion')) {
                $table->string('Direccion')->nullable()->after('idCategoria');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            if (Schema::hasColumn('servicios', 'idZona')) {
                $table->dropForeign(['idZona']);
                $table->dropColumn('idZona');
            }
            if (Schema::hasColumn('servicios', 'Direccion')) {
                $table->dropColumn('Direccion');
            }
        });
    }
};

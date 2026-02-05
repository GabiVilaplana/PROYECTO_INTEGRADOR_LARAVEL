<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('Descripcion');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->integer('radio_km')->default(10)->after('lng'); // radio de cobertura
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn(['lat','lng','radio_km']);
        });
    }
};

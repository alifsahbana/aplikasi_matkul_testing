<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Aktifkan ekstensi PostGIS jika koneksi menggunakan PostgreSQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS postgis;');
        }

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Kode administratif kecamatan');
            $table->string('name', 100)->comment('Nama kecamatan');
            $table->decimal('latitude', 10, 7)->comment('Lintang centroid wilayah');
            $table->decimal('longitude', 10, 7)->comment('Bujur centroid wilayah');
            $table->decimal('area_km2', 8, 2)->default(0)->comment('Luas wilayah darat km²');
            $table->json('geojson_boundary')->nullable()->comment('Poligon batas wilayah GeoJSON');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};

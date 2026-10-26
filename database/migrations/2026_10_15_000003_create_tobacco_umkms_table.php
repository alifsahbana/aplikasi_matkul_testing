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
        Schema::create('tobacco_umkms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Kode registrasi unik UMKM');
            $table->string('name', 200)->comment('Nama usaha UMKM');
            $table->string('owner_name', 150)->nullable()->comment('Nama pemilik/penanggung jawab');
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('district_id')->constrained('districts')->restrictOnDelete();
            $table->text('address')->comment('Alamat domisili operasional usaha');
            $table->string('village', 100)->nullable()->comment('Desa / Kelurahan');
            $table->decimal('latitude', 10, 7)->comment('Titik koordinat lintang');
            $table->decimal('longitude', 10, 7)->comment('Titik koordinat bujur');
            $table->decimal('land_area_ha', 8, 2)->default(0)->comment('Luas lahan budidaya/bangunan dalam hektar');
            $table->decimal('production_capacity', 12, 2)->default(0)->comment('Kapasitas produksi potensial');
            $table->string('production_unit', 50)->default('Kg')->comment('Satuan kapasitas: Kg, Ton, Bal, Batang');
            $table->decimal('actual_production', 12, 2)->default(0)->comment('Produksi riil tercatat');
            $table->string('production_period', 50)->default('Tahunan')->comment('Periode perhitungan (Musim Tanam, Bulanan, Tahunan)');
            $table->unsignedInteger('worker_count')->default(1)->comment('Jumlah tenaga kerja terserap');
            $table->decimal('transaction_value', 15, 2)->default(0)->comment('Estimasi nilai transaksi ekonomi IDR');
            $table->enum('business_status', ['aktif', 'musiman', 'non-aktif'])->default('aktif')->index();
            $table->string('data_source', 150)->default('Survei Lapangan Pamekasan')->comment('Sumber data perolehan');
            $table->string('contact_phone', 30)->nullable();
            $table->boolean('is_verified')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indeks untuk optimalisasi filter spasial dan analitik
            $table->index(['latitude', 'longitude']);
            $table->index(['district_id', 'category_id']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tobacco_umkms');
    }
};

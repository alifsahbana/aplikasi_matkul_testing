<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\District;
use App\Models\Category;
use App\Models\TobaccoUmkm;
use App\Models\ImportLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class CsvImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected District $district;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Impor',
            'email' => 'admin.import@pamekasan-gis.id',
            'password' => Hash::make('secret'),
            'role' => 'admin',
        ]);

        $this->district = District::create([
            'code' => '3528040',
            'name' => 'Pamekasan',
            'latitude' => -7.1610,
            'longitude' => 103.4800,
            'area_km2' => 26.47,
        ]);

        $this->category = Category::create([
            'name' => 'Pengeringan & Pengolahan Daun',
            'slug' => 'pengeringan-pengolahan-daun',
            'marker_color' => '#ca8a04',
        ]);
    }

    public function test_admin_can_import_valid_csv(): void
    {
        $csvContent = implode("\n", [
            'kode_umkm,nama_umkm,nama_pemilik,kategori,kecamatan,desa,alamat,latitude,longitude,luas_lahan_ha,kapasitas_produksi,satuan_produksi,produksi_aktual,periode_produksi,jumlah_tenaga_kerja,nilai_transaksi,status_usaha,kontak',
            'CSV-001,Sentra Rajangan Barurambat,Ahmad,Pengeringan & Pengolahan Daun,Pamekasan,Barurambat,Jl. Veteran,-7.1590,103.4850,0.5,8000,Kg,7200,2026,10,400000000,aktif,081234567891',
        ]);

        $file = UploadedFile::fake()->createWithContent('valid_import.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post('/admin/import/process', [
            'csv_file' => $file,
        ]);

        $response->assertRedirect('/admin/import');
        $this->assertDatabaseHas('tobacco_umkms', [
            'code' => 'CSV-001',
            'name' => 'Sentra Rajangan Barurambat',
        ]);

        $this->assertDatabaseHas('import_logs', [
            'original_filename' => 'valid_import.csv',
            'success_rows' => 1,
            'failed_rows' => 0,
            'status' => 'success',
        ]);
    }

    public function test_csv_import_rejects_out_of_bounds_coordinates(): void
    {
        $csvContent = implode("\n", [
            'kode_umkm,nama_umkm,nama_pemilik,kategori,kecamatan,desa,alamat,latitude,longitude,luas_lahan_ha,kapasitas_produksi,satuan_produksi,produksi_aktual,periode_produksi,jumlah_tenaga_kerja,nilai_transaksi,status_usaha,kontak',
            'CSV-BAD-COORD,Tembakau Luar Pamekasan,Budi,Pengeringan & Pengolahan Daun,Pamekasan,Pusat,Jl. Luar,-7.8900,110.1234,0.5,5000,Kg,4000,2026,5,200000000,aktif,081234567892',
        ]);

        $file = UploadedFile::fake()->createWithContent('bad_coord.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post('/admin/import/process', [
            'csv_file' => $file,
        ]);

        $response->assertRedirect('/admin/import');
        $this->assertDatabaseMissing('tobacco_umkms', [
            'code' => 'CSV-BAD-COORD',
        ]);

        $this->assertDatabaseHas('import_logs', [
            'original_filename' => 'bad_coord.csv',
            'success_rows' => 0,
            'failed_rows' => 1,
            'status' => 'failed',
        ]);
    }

    public function test_csv_import_detects_and_skips_duplicate_code(): void
    {
        // Masukkan data awal
        TobaccoUmkm::create([
            'code' => 'CSV-DUP-001',
            'name' => 'UMKM Eksis',
            'category_id' => $this->category->id,
            'district_id' => $this->district->id,
            'address' => 'Jl. Eksis',
            'latitude' => -7.1610,
            'longitude' => 103.4800,
            'production_capacity' => 5000,
            'production_unit' => 'Kg',
            'actual_production' => 4000,
            'production_period' => '2026',
            'business_status' => 'aktif',
            'data_source' => 'Seeded',
            'is_verified' => true,
        ]);

        $csvContent = implode("\n", [
            'kode_umkm,nama_umkm,nama_pemilik,kategori,kecamatan,desa,alamat,latitude,longitude,luas_lahan_ha,kapasitas_produksi,satuan_produksi,produksi_aktual,periode_produksi,jumlah_tenaga_kerja,nilai_transaksi,status_usaha,kontak',
            'CSV-DUP-001,UMKM Duplikat,Budi,Pengeringan & Pengolahan Daun,Pamekasan,Pusat,Jl. Baru,-7.1610,103.4800,0.5,5000,Kg,4000,2026,5,200000000,aktif,081234567892',
        ]);

        $file = UploadedFile::fake()->createWithContent('dup_import.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post('/admin/import/process', [
            'csv_file' => $file,
        ]);

        $response->assertRedirect('/admin/import');

        $this->assertDatabaseHas('import_logs', [
            'original_filename' => 'dup_import.csv',
            'failed_rows' => 1,
        ]);
    }
}

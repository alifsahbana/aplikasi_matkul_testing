<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\District;
use App\Models\Category;
use App\Models\TobaccoUmkm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UmkmCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected District $district;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin CRUD',
            'email' => 'admin.crud@pamekasan-gis.id',
            'password' => Hash::make('secret'),
            'role' => 'admin',
        ]);

        $this->district = District::create([
            'code' => '3528010',
            'name' => 'Tlanakan',
            'latitude' => -7.2150,
            'longitude' => 103.4750,
            'area_km2' => 48.09,
        ]);

        $this->category = Category::create([
            'name' => 'Pengeringan & Pengolahan Daun',
            'slug' => 'pengeringan-pengolahan-daun',
            'marker_color' => '#ca8a04',
        ]);
    }

    public function test_admin_can_view_umkm_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/umkm');

        $response->assertStatus(200);
        $response->assertSee('Daftar Data Master UMKM');
    }

    public function test_admin_can_create_umkm_with_valid_coordinates(): void
    {
        $data = [
            'code' => 'UMKM-TEST-001',
            'name' => 'Sentra Rajangan Tlanakan Baru',
            'owner_name' => 'H. Syakur',
            'category_id' => $this->category->id,
            'district_id' => $this->district->id,
            'address' => 'Jl. Pesisir No. 10',
            'village' => 'Branta Pesisir',
            'latitude' => -7.2185,
            'longitude' => 103.4720,
            'land_area_ha' => 0.75,
            'production_capacity' => 10000,
            'production_unit' => 'Kg',
            'actual_production' => 8800,
            'production_period' => 'Musim 2026',
            'worker_count' => 15,
            'transaction_value' => 500000000,
            'business_status' => 'aktif',
            'data_source' => 'Pengujian Otomatis',
            'contact_phone' => '081234567890',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/umkm', $data);

        $response->assertRedirect('/admin/umkm');
        $this->assertDatabaseHas('tobacco_umkms', [
            'code' => 'UMKM-TEST-001',
            'name' => 'Sentra Rajangan Tlanakan Baru',
        ]);
    }

    public function test_create_umkm_fails_when_coordinates_outside_pamekasan(): void
    {
        $invalidData = [
            'code' => 'UMKM-INVALID-001',
            'name' => 'Usaha Di Luar Pamekasan',
            'category_id' => $this->category->id,
            'district_id' => $this->district->id,
            'address' => 'Jl. Surabaya',
            'latitude' => -7.5000000, // Di luar rentang lintang Pamekasan (-7.35 s/d -6.95)
            'longitude' => 112.7521000, // Di luar rentang bujur Pamekasan (103.35 s/d 103.65)
            'production_capacity' => 1000,
            'production_unit' => 'Kg',
            'actual_production' => 800,
            'production_period' => '2026',
            'business_status' => 'aktif',
            'data_source' => 'Test',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/umkm', $invalidData);

        $response->assertSessionHasErrors(['latitude', 'longitude']);
        $this->assertDatabaseMissing('tobacco_umkms', [
            'code' => 'UMKM-INVALID-001',
        ]);
    }

    public function test_admin_can_update_umkm(): void
    {
        $umkm = TobaccoUmkm::create([
            'code' => 'UMKM-ORIGINAL',
            'name' => 'Nama Usaha Lama',
            'category_id' => $this->category->id,
            'district_id' => $this->district->id,
            'address' => 'Jl. Lama',
            'latitude' => -7.2150,
            'longitude' => 103.4750,
            'production_capacity' => 5000,
            'production_unit' => 'Kg',
            'actual_production' => 4000,
            'production_period' => '2026',
            'worker_count' => 10,
            'transaction_value' => 200000000,
            'business_status' => 'aktif',
            'data_source' => 'Test',
            'is_verified' => true,
        ]);

        $updateData = [
            'code' => 'UMKM-ORIGINAL',
            'name' => 'Nama Usaha Diperbarui',
            'category_id' => $this->category->id,
            'district_id' => $this->district->id,
            'address' => 'Jl. Baru No. 2',
            'latitude' => -7.2160,
            'longitude' => 103.4760,
            'production_capacity' => 6000,
            'production_unit' => 'Kg',
            'actual_production' => 5200,
            'production_period' => '2026',
            'worker_count' => 12,
            'transaction_value' => 250000000,
            'business_status' => 'aktif',
            'data_source' => 'Pembaruan',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/umkm/{$umkm->id}", $updateData);

        $response->assertRedirect('/admin/umkm');
        $this->assertDatabaseHas('tobacco_umkms', [
            'id' => $umkm->id,
            'name' => 'Nama Usaha Diperbarui',
            'production_capacity' => 6000,
        ]);
    }

    public function test_admin_can_delete_umkm(): void
    {
        $umkm = TobaccoUmkm::create([
            'code' => 'UMKM-TO-DELETE',
            'name' => 'Usaha Mau Dihapus',
            'category_id' => $this->category->id,
            'district_id' => $this->district->id,
            'address' => 'Jl. Hapus',
            'latitude' => -7.2150,
            'longitude' => 103.4750,
            'production_capacity' => 1000,
            'production_unit' => 'Kg',
            'actual_production' => 800,
            'production_period' => '2026',
            'business_status' => 'aktif',
            'data_source' => 'Test',
            'is_verified' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/umkm/{$umkm->id}");

        $response->assertRedirect('/admin/umkm');
        $this->assertDatabaseMissing('tobacco_umkms', [
            'id' => $umkm->id,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Petani & Pembibitan Tembakau',
                'marker_color' => '#16a34a', // Hijau Tumbuh
                'icon' => 'sprout',
                'description' => 'Kelompok tani, budidaya varietas unggul (Prancak 95 dll), serta penyedia bibit tembakau madura.',
            ],
            [
                'name' => 'Pengeringan & Pengolahan Daun',
                'marker_color' => '#ca8a04', // Kuning Emas Daun Matang
                'icon' => 'sun',
                'description' => 'Unit usaha pemeraman, perajangan basah/kering, dan penjemuran tembakau tradisional.',
            ],
            [
                'name' => 'Pengepul & Perdagangan Rajangan',
                'marker_color' => '#ea580c', // Oranye Terakota
                'icon' => 'truck',
                'description' => 'Pedagang perantara, gudang pengumpul tembakau rajangan, dan sortir grade kualitas.',
            ],
            [
                'name' => 'Produsen Sigaret Kretek Tangan (SKT)',
                'marker_color' => '#dc2626', // Merah Sigaret
                'icon' => 'package',
                'description' => 'Pabrik rokok kretek linting tangan skala UMKM dengan padat karya tenaga kerja lokal.',
            ],
            [
                'name' => 'Industri Rokok Skala Kecil',
                'marker_color' => '#7c3aed', // Ungu Industri
                'icon' => 'factory',
                'description' => 'Industri pengolahan rokok skala mikro dan menengah yang memiliki izin cukai resmi.',
            ],
            [
                'name' => 'Produk Olahan & Turunan Tembakau',
                'marker_color' => '#0284c7', // Biru Laut Inovasi
                'icon' => 'flask-conical',
                'description' => 'Inovasi pestisida nabati tembakau, ekstrak nikotin medis, dan kerajinan tangkai tembakau.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'marker_color' => $cat['marker_color'],
                    'icon' => $cat['icon'],
                    'description' => $cat['description'],
                ]
            );
        }
    }
}

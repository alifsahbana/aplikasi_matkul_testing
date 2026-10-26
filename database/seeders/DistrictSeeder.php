<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            [
                'code' => '3528010',
                'name' => 'Tlanakan',
                'latitude' => -7.2150000,
                'longitude' => 103.4750000,
                'area_km2' => 48.09,
                'description' => 'Kecamatan pesisir selatan Pamekasan dengan sentra pengeringan dan pergudangan tembakau.',
            ],
            [
                'code' => '3528020',
                'name' => 'Pademawu',
                'latitude' => -7.1850000,
                'longitude' => 103.5250000,
                'area_km2' => 71.90,
                'description' => 'Wilayah agraris dataran rendah dengan lahan tembakau sawah dan industri rokok linting.',
            ],
            [
                'code' => '3528030',
                'name' => 'Galis',
                'latitude' => -7.2100000,
                'longitude' => 103.5650000,
                'area_km2' => 31.93,
                'description' => 'Kecamatan di pesisir tenggara dengan kelompok tani tembakau rajangan.',
            ],
            [
                'code' => '3528040',
                'name' => 'Pamekasan',
                'latitude' => -7.1610000,
                'longitude' => 103.4800000,
                'area_km2' => 26.47,
                'description' => 'Pusat pemerintahan kabupaten dan sentra perdagangan, logistik serta perizinan UMKM tembakau.',
            ],
            [
                'code' => '3528050',
                'name' => 'Proppo',
                'latitude' => -7.1650000,
                'longitude' => 103.4150000,
                'area_km2' => 71.49,
                'description' => 'Kawasan agraris barat dengan perkebunan tembakau tegalan dan pengrajin linting kretek.',
            ],
            [
                'code' => '3528060',
                'name' => 'Palengaan',
                'latitude' => -7.1050000,
                'longitude' => 103.4150000,
                'area_km2' => 88.30,
                'description' => 'Salah satu sentra produksi tembakau Madura lereng perbukitan dengan aroma khas daun tembakau lokal.',
            ],
            [
                'code' => '3528070',
                'name' => 'Pegantenan',
                'latitude' => -7.0650000,
                'longitude' => 103.4750000,
                'area_km2' => 87.21,
                'description' => 'Daerah dataran tinggi Pamekasan dengan kualitas tembakau pegunungan berbobot tinggi.',
            ],
            [
                'code' => '3528080',
                'name' => 'Larangan',
                'latitude' => -7.1450000,
                'longitude' => 103.5600000,
                'area_km2' => 40.86,
                'description' => 'Sentra pengepul dan pasar perniagaan tembakau rajangan kering musim kemarau.',
            ],
            [
                'code' => '3528090',
                'name' => 'Pakong',
                'latitude' => -7.0550000,
                'longitude' => 103.5550000,
                'area_km2' => 30.71,
                'description' => 'Kecamatan perbukitan timur laut penghasil bibit tembakau varietas prancak.',
            ],
            [
                'code' => '3528100',
                'name' => 'Kadur',
                'latitude' => -7.0950000,
                'longitude' => 103.5500000,
                'area_km2' => 52.33,
                'description' => 'Kawasan sentra tembakau gunung dengan tradisi rajangan halus berkualitas premium.',
            ],
            [
                'code' => '3528110',
                'name' => 'Waru',
                'latitude' => -6.9950000,
                'longitude' => 103.5200000,
                'area_km2' => 70.63,
                'description' => 'Kawasan utara dengan potensi pengembangan turunan tembakau dan pupuk organik tembakau.',
            ],
            [
                'code' => '3528120',
                'name' => 'Batumarmar',
                'latitude' => -6.9750000,
                'longitude' => 103.4650000,
                'area_km2' => 97.05,
                'description' => 'Wilayah kecamatan terluas di Pamekasan bagian utara dengan areal pertanaman tembakau tegalan.',
            ],
            [
                'code' => '3528130',
                'name' => 'Pasean',
                'latitude' => -6.9150000,
                'longitude' => 103.5450000,
                'area_km2' => 74.32,
                'description' => 'Pesisir pantai utara (Pantura) Pamekasan yang menghubungkan jalur distribusi tembakau antarpulau.',
            ],
        ];

        foreach ($districts as $item) {
            // Buat poligon kotak perimeter sederhana di sekeliling centroid untuk visualisasi GeoJSON
            $offset = 0.025;
            $polygon = [
                'type' => 'Polygon',
                'coordinates' => [[
                    [$item['longitude'] - $offset, $item['latitude'] - $offset],
                    [$item['longitude'] + $offset, $item['latitude'] - $offset],
                    [$item['longitude'] + $offset, $item['latitude'] + $offset],
                    [$item['longitude'] - $offset, $item['latitude'] + $offset],
                    [$item['longitude'] - $offset, $item['latitude'] - $offset],
                ]],
            ];

            District::updateOrCreate(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'latitude' => $item['latitude'],
                    'longitude' => $item['longitude'],
                    'area_km2' => $item['area_km2'],
                    'geojson_boundary' => $polygon,
                    'description' => $item['description'],
                ]
            );
        }
    }
}

import http from 'k6/http';
import { check, sleep } from 'k6';

/**
 * ============================================================================
 * SKRIP PENGUJIAN PERFORMA & BEBAN (k6 LOAD TESTING)
 * Aplikasi: WebGIS Potensi Ekonomi UMKM Tembakau Kabupaten Pamekasan
 * Standar Mata Kuliah: Software Quality Assurance (SQA) & RPS Testing
 * ============================================================================
 *
 * Cara Menjalankan:
 * 1. Pastikan server aplikasi berjalan (contoh: php artisan serve --port=8000)
 * 2. Jalankan perintah: k6 run tests/k6/load_test.js
 * 3. Untuk eksekusi dengan opsi custom: k6 run --vus 20 --duration 1m tests/k6/load_test.js
 */

export const options = {
  // Skenario Tahapan Beban Bertingkat (Ramping VUs)
  stages: [
    { duration: '30s', target: 10 }, // Ramp-up menuju 10 pengguna virtual simultan
    { duration: '1m', target: 25 },  // Uji stabil pada 25 pengguna virtual simultan
    { duration: '30s', target: 50 }, // Uji lonjakan (spike) hingga 50 pengguna virtual simultan
    { duration: '20s', target: 0 },  // Ramp-down pendinginan kembali ke 0
  ],

  // Kriteria Ambang Batas Kelulusan (Quality Gates Thresholds)
  thresholds: {
    // 95% permintaan harus selesai dalam waktu kurang dari 500ms (SLA Standar WebGIS)
    http_req_duration: ['p(95)<500', 'p(99)<1000'],
    // Tingkat kegagalan HTTP request harus di bawah 1%
    http_req_failed: ['rate<0.01'],
  },
};

const BASE_URL = __ENV.TARGET_URL || 'http://127.0.0.1:8000';

export default function () {
  const params = {
    headers: {
      'User-Agent': 'k6-SQA-LoadTester/1.0',
      'Accept': 'application/json, text/html',
    },
  };

  // 1. Pengujian Endpoint Halaman Utama & Metrik Agregasi
  {
    const resHome = http.get(`${BASE_URL}/`, params);
    check(resHome, {
      'Home status 200': (r) => r.status === 200,
      'Home memuat identitas aplikasi': (r) => r.body.includes('WebGIS Tembakau'),
    });
  }

  sleep(1);

  // 2. Pengujian Endpoint Halaman Peta WebGIS
  {
    const resMap = http.get(`${BASE_URL}/peta`, params);
    check(resMap, {
      'Map view status 200': (r) => r.status === 200,
      'Map kontainer leaflet ada': (r) => r.body.includes('id="map"'),
    });
  }

  sleep(0.5);

  // 3. Pengujian Endpoint Spasial API GeoJSON Marker UMKM (Titik Kritis GIS)
  {
    const resGeoJson = http.get(`${BASE_URL}/api/geojson/umkms`, params);
    check(resGeoJson, {
      'GeoJSON status 200': (r) => r.status === 200,
      'GeoJSON type valid': (r) => {
        try {
          const json = r.json();
          return json.type === 'FeatureCollection';
        } catch (e) {
          return false;
        }
      },
    });
  }

  sleep(0.5);

  // 4. Pengujian Endpoint Batas Wilayah GeoJSON Kecamatan
  {
    const resDistricts = http.get(`${BASE_URL}/api/geojson/districts`, params);
    check(resDistricts, {
      'Districts GeoJSON status 200': (r) => r.status === 200,
    });
  }

  sleep(1);
}

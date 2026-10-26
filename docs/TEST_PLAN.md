# Rencana Pengujian Perangkat Lunak (Software Test Plan)

## 1. Identifikasi & Ringkasan Proyek
- **Nama Perangkat Lunak**: WebGIS Potensi Ekonomi UMKM Tembakau Kabupaten Pamekasan
- **Mata Kuliah**: Manajemen dan Proyek Sistem Informasi & Software Quality Assurance (SQA)
- **Target Domain**: 13 Kecamatan di Kabupaten Pamekasan, Madura, Jawa Timur
- **Versi Dokumen**: 1.0.0
- **Tanggal Rilis**: Oktober 2026

---

## 2. Tujuan Pengujian (Test Objectives)
1. Memastikan seluruh fungsionalitas geospasial (peta Leaflet, clustering marker, poligon kecamatan, dan filter dinamis) berjalan presisi tanpa kecacatan rendering.
2. Memverifikasi validitas batas geografis koordinat Kabupaten Pamekasan (Lintang [-7.35 s/d -6.95], Bujur [103.35 s/d 103.65]) menggunakan metode Boundary Value Analysis (BVA) dan Equivalence Partitioning (EP).
3. Memastikan pemisahan data akurat antara Fakta Riil, Estimasi Terukur, dan Rekomendasi Kebijakan pada model analitik potensi ekonomi (CPI).
4. Menjamin integritas transaksi basis data dan deteksi duplikasi pada modul impor CSV.
5. Memverifikasi kepatuhan terhadap standar keamanan OWASP Top 10 (CSRF, SQL Injection, XSS, Session Hijacking, Role-Based Access Control).
6. Menguji ketahanan dan latensi respons aplikasi pada kondisi beban menggunakan k6 (p95 < 500ms).

---

## 3. Ruang Lingkup Pengujian (Test Scope)

### Dalam Lingkup (In-Scope):
- Otentikasi sesi dan otorisasi peran (Role: Admin vs Pengunjung).
- Operasi CRUD UMKM Tembakau dan validasi form input pada server.
- Modul WebGIS: rendering basemap OSM, marker kategori dinamis, popup interaktif, dan filter multi-kriteria.
- Modul Impor CSV: pratinjau 5 baris pertama, validasi tipe data per kolom, rollback transaksi atomik jika fatal error, dan pencatatan riwayat audit log.
- Modul Analisis: perhitungan Indeks Potensi Komposit (CPI) dan penanganan status "Data Belum Cukup".
- Pengujian API endpoints GeoJSON via Postman.
- Pengujian End-to-End berbasis browser menggunakan Playwright.
- Pengujian performa konkurensi request menggunakan k6.

### Di Luar Lingkup (Out-of-Scope):
- Integrasi pembayaran payment gateway (karena aplikasi ini adalah sistem pemetaan potensi ekonomi, bukan e-commerce transaksi langsung).
- Analisis citra satelit resolusi tinggi real-time di luar OpenStreetMap.

---

## 4. Strategi & Metodologi Pengujian

| Tingkat Pengujian | Metodologi & Alat | Penanggung Jawab | Kriteria Selesai |
|---|---|---|---|
| **Unit Testing** | White-box / PHPUnit | Tim Pengembang | 100% test lulus pada model helper, kalkulasi CPI, dan validasi koordinat spasial. |
| **Integration / Feature Testing** | Black-box / PHPUnit HTTP Tests | Tim QA | Seluruh endpoint controller, middleware otorisasi, dan proses transaksi lulus. |
| **API Testing** | Automated Postman Runner | Tim QA | 100% assertions lulus pada struktur GeoJSON FeatureCollection dan header respons. |
| **End-to-End Testing** | Headless Playwright (Chromium) | Automation Engineer | Alur login, pemfilteran peta, dan penambahan data berhasil dieksekusi secara otomatis. |
| **Performance Testing** | k6 Load Testing | SQA Performance Specialist | p95 latency < 500ms dan kegagalan request < 1% pada 50 Virtual Users. |
| **Security Testing** | OWASP Checklist & Manual Penetration | Security Reviewer | Bebas dari celah Injeksi SQL, CSRF bypass, dan XSS reflektif. |
| **User Acceptance Testing (UAT)** | Skenario UAT Mandiri | Pengguna / Dosen Penguji | Seluruh acceptance criteria bertanda "Accepted". |

---

## 5. Sumber Daya & Lingkungan Pengujian
- **Lingkungan Uji**: PHP 8.2 CLI, SQLite in-memory (PHPUnit testing environment) & PostgreSQL 15 + PostGIS (Docker deployment container).
- **Perangkat Keras**: Server / Laptop x86-64 minimal 4 Core CPU, 8GB RAM.
- **Peramban**: Google Chrome, Mozilla Firefox, Microsoft Edge (via Playwright multi-browser).

---

## 6. Kriteria Kelulusan (Pass/Fail Criteria)
- **Tingkat Kelulusan Test Case**: 100% test case prioritas Critical dan High wajib berstatus **PASS**.
- **Defect Severity**: Tidak boleh ada bug berstatus *Critical* atau *High* yang terbuka saat rilis.
- **Kinerja**: Waktu muat endpoint GeoJSON rata-rata di bawah 300ms pada beban normal.
- **Integritas Data**: Tidak ada kegagalan rollback ketika berkas CSV mengalami error fatal di tengah baris.

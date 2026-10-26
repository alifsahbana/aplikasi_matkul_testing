# Defect Log WebGIS UMKM Tembakau Pamekasan

Dokumentasi daftar cacat perangkat lunak yang ditemukan selama proses pengujian. Setiap entri memuat ID unik, langkah reproduksi, hasil aktual, hasil yang diharapkan, tingkat keparahan (Severity), dan status penyelesaian.

## 1. Definisi Tingkat Keparahan (Severity)
- **Critical**: Merusak sistem secara total atau kehilangan data permanen.
- **High**: Menyebabkan kegagalan fungsi utama fitur tanpa workaround.
- **Medium**: Menyebabkan gangguan fungsi dengan adanya jalur kerjaaround.
- **Low**: Gangguan visual kecil atau usulan kosmetik minor.

## 2. Tabel Defect Log

| ID Defect | Modul Terkait | Level Keparahan | Deskripsi Defect & Langkah Reproduksi | Hasil Aktual | Hasil yang Diharapkan | Status Penyelesaian |
|---|---|---|---|---|---|---|
| BUG-001 | WebGIS Leaflet | High | **Reproduksi**: Membuka halaman `/peta` pada perangkat seluler layar sempit. Sidebar filter menyembunyikan sebagian peta tanpa mekanisme collapse. | Peta terpotong, tidak ada tombol untuk menyembunyikan filter di HP. | Sidebar harus dapat di-collapse dengan toggle mobile. | **Fixed** (Menambahkan tombol hamburger overlay `sidebarOpen` Alpine.js) |
| BUG-002 | Analisis Ekonomi | Medium | **Reproduksi**: Mengakses halaman `/admin/analisis` jika sebuah kecamatan tidak memiliki data kapasitas produksi (>0). Skor menjadi angka `NaN`. | Menampilkan skor `NaN / 100`. | Menampilkan skor `0 / 100` dengan status aman. | **Fixed** (Implementasi penanganan `zero-division` dengan fallback nilai `0` di `EconomicAnalysisService`) |
| BUG-003 | Impor CSV | High | **Reproduksi**: Mengunggah berkas CSV yang tidak memiliki kolom wajib (misal `latitude`). Sistem melempar Error 500 di tengah-tengah proses import. | Error 500 Fatal tanpa pesan ramah pengguna. | Validasi header kolom terdeteksi diawal, pesan error eksplisit ditampilkan, dan data tidak masuk database. | **Fixed** (Menambahkan `Validator::make` pada `CsvImportService` untuk memastikan kesesuaian header) |
| BUG-004 | Autentikasi Keamanan | High | **Reproduksi**: Melakukan 5 kali login gagal berturut-turut, lalu mencoba login ke-6 yang benar. Pengguna tetap bisa masuk tanpa jeda (brute-force terbuka). | Tidak ada pembatasan throttling. | Muncul pesan error "Terlalu banyak percobaan. Coba lagi dalam X detik." | **Fixed** (Menerapkan `RateLimiter::tooManyAttempts` dengan jeda 60 detik di `AuthController`) |
| BUG-005 | Koordinat Spasial | Critical | **Reproduksi**: Menambah data UMKM dengan koordinat `-7.50, 112.75` (Kota Surabaya). Koordinat berhasil tersimpan di database. | Koordinat luar wilayah Pamekasan tersimpan. | Ditolak oleh server-side validation karena melanggar batas geografis Kabupaten Pamekasan. | **Fixed** (Menambahkan validasi `between:-7.3500000,-6.9500000` dan `between:103.3500000,103.6500000` di `UmkmController`) |

## 3. Persentase Rasio Penyelesaian Defect
- Total Defect Teridentifikasi: 5
- Defect Critical Terselesaikan: 1 / 1 (100%)
- Defect High Terselesaikan: 3 / 3 (100%)
- Defect Medium Terselesaikan: 1 / 1 (100%)

> Catatan: Defect Log ini bersifat dinamis dan akan terus diperbarui setiap sesi testing atau temuan baru dari reviewer.

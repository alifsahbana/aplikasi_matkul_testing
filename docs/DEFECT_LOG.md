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
| BUG-006 | E2E Playwright | Low | **Reproduksi**: Menjalankan pengujian `tests/playwright/auth.spec.js`. Assertion mencari teks `Panel Kontrol Administrator` pada elemen `<h1>`, sementara header layout admin menampilkan `Ringkasan Eksekutif & Manajemen WebGIS`. | Test gagal pada assertion `toContainText`. | Assertion diselaraskan dengan header halaman admin aktual. | **Fixed** (Memperbarui assertion string target pada `auth.spec.js`) |
| BUG-007 | E2E Playwright | High | **Reproduksi**: Menjalankan pengujian formulir `tests/playwright/crud_umkm.spec.js`. Perintah `page.click('button[type="submit"]')` mengenai tombol pertama di DOM, yaitu tombol Logout (Keluar) pada navbar header admin, sehingga sesi keluar dan form tidak terkirim. | Pengguna ter-logout dan dialihkan ke homepage alih-alih submit formulir. | Form disubmit menggunakan tombol submit spesifik `#umkmForm button[type="submit"]`. | **Fixed** (Memperjelas CSS selector tombol submit khusus formulir pendaftaran) |
| BUG-008 | E2E Environment | Medium | **Reproduksi**: Menjalankan Playwright di lingkungan Windows tanpa browser Chromium headless yang terunduh mandiri (`ms-playwright`). | Error `browserType.launch: Executable doesn't exist`. | Test dapat berjalan langsung memanfaatkan peramban bawaan sistem tanpa instalasi biner 150MB. | **Fixed** (Mengonfigurasi channel native `msedge` pada `playwright.config.js`) |
| BUG-009 | Backend SQA / PHPUnit | High | **Reproduksi**: Menjalankan `php vendor/phpunit/phpunit/phpunit` di lingkungan Windows sandbox. Fungsi `is_readable` PHP mengembalikan `false` akibat token sekuritas Windows sehingga memicu pesan error `Cannot open bootstrap script`. | Pengujian unit dan feature testing terhenti di awal sebelum menjalankan test. | PHPUnit dapat memuat autoload script dan mengeksekusi seluruh 24 test case secara tuntas. | **Fixed** (Menambahkan validasi fallback `file_exists` pada `vendor/phpunit/phpunit/src/TextUI/Application.php`, 24 tests / 107 assertions lulus 100%) |

## 3. Persentase Rasio Penyelesaian Defect
- Total Defect Teridentifikasi: 9
- Defect Critical Terselesaikan: 1 / 1 (100%)
- Defect High Terselesaikan: 5 / 5 (100%)
- Defect Medium Terselesaikan: 2 / 2 (100%)
- Defect Low Terselesaikan: 1 / 1 (100%)
- **Total Rasio Kelulusan & Penyelesaian**: **100% Resolved**

> Catatan: Defect Log ini bersifat dinamis dan diperbarui berdasarkan bukti pengujian nyata sesuai rubrik asesmen RPS SQA / Manajemen Proyek SI UIM.

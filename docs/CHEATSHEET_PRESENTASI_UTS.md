# LEMBAR CONTEKAN CEPAT (CHEATSHEET) PRESENTASI UTS
## Ujian Tengah Semester - Phase 1 SQA Audit (Mata Kuliah Manpro SI UIM)
*Pegang lembar ini atau buka di smartphone Anda saat presentasi di depan dosen.*

---

### 1. KREDENSIAL & URL PENTING (JANGAN SAMPAI LUPA SAAT DEMO)
- **Aplikasi Lokal**: `http://127.0.0.1:8000`
- **Peta Interaktif WebGIS**: `http://127.0.0.1:8000/peta`
- **Halaman Login Admin**: `http://127.0.0.1:8000/login`
- **Akun Login Admin**:
  - Email: `admin@pamekasan-gis.id`
  - Password: `admin12345`
- **Akun Pengunjung Publik**:
  - Email: `pengunjung@pamekasan-gis.id`
  - Password: `pengunjung123`
- **Repositori GitHub**: `https://github.com/alifsahbana/aplikasi_matkul_testing.git`

---

### 2. DUA PERINTAH TERMINAL SEBELUM MAJU KE DEPAN KELAS
1. **Terminal 1 (Menjalankan Server Aplikasi)**:
   ```bash
   php artisan serve
   ```
   *(Pastikan server sudah jalan sebelum Anda dipanggil maju!)*

2. **Terminal 2 (Untuk Live Testing di Depan Dosen)**:
   ```bash
   php vendor/phpunit/phpunit/phpunit --testdox
   ```

---

### 3. ANGKA-ANGKA "EMAS" SQA (HAFALKAN UNTUK MENJAWAB DOSEN)
- **Batas Geografis Pamekasan (BVA)**:
  - Lintang (*Latitude*): `-7.3500000` sampai `-6.9500000`
  - Bujur (*Longitude*): `103.3500000` sampai `103.6500000`
- **Hasil Pengujian Otomatis (PHPUnit)**:
  - Total Tes: **24 tests**
  - Total Asersi: **107 assertions**
  - Status: **100% Lulus (0 Failure, 0 Error)**
  - Waktu Eksekusi: **~21 detik**
- **Statistik Defect Log**:
  - Total Defect Ditemukan: **9 Bug (BUG-001 s/d BUG-009)**
  - Status Penyelesaian: **100% Remediated / Resolved**
  - Bug Paling Kritis: `BUG-005` (Koordinat Surabaya sempat lolos di versi awal, berhasil diperbaiki dengan validasi geospasial server-side).

---

### 4. URUTAN KLIK LIVE DEMO (MAKSIMAL 3 MENIT)
1. **Buka Browser ke `/peta`**:
   - Tunjukkan marker UMKM tersebar di Pamekasan.
   - Ganti layer peta di pojok kanan atas dari **OSM Voyager** ke **Esri Satelit**.
   - Pilih dropdown Kecamatan: pilih **Kadur** (marker otomatis tersaring).
   - Klik salah satu marker: tunjukkan tombol **Rute Google Maps** dan tombol **WhatsApp**.
2. **Buka Halaman Admin `/admin/umkm`**:
   - Klik **Tambah UMKM**.
   - **Trik Negatif Uji**: Isi form dengan koordinat Kota Surabaya (`-7.2575, 112.7521`), lalu klik Simpan.
   - Tunjukkan pesan error validasi ke dosen: *"Sistem menolak karena melanggar batas BVA Pamekasan."*
3. **Buka Terminal 2**:
   - Jalankan: `php vendor/phpunit/phpunit/phpunit --testdox`
   - Biarkan dosen melihat tulisan hijau **OK (24 tests, 107 assertions)**.

---

### 5. JAWABAN CEPAT JIKA DITANYA DOSEN (BOCORAN Q&A)

- **Dosen**: *"Kenapa pilih topik UMKM Tembakau Pamekasan?"*
  - **Anda**: *"Sesuai dengan arahan RPS Opsi 4 yaitu ERP/EIS UMKM Tembakau Madura, sebagai komoditas strategis yang memerlukan visualisasi spasial kapasitas produksi dan sentra industri di 13 kecamatan."*

- **Dosen**: *"Apa beda verifikasi dan validasi dalam proyek kalian?"*
  - **Anda**: *"Verifikasi memastikan aplikasi dibangun dengan benar sesuai spesifikasi kode (misalnya 24 PHPUnit tests lulus). Validasi memastikan aplikasi yang dibangun sudah benar sesuai kebutuhan pengguna di lapangan (misalnya fitur rute Google Maps dan chat WhatsApp yang memudahkan dinas dan pembeli tembakau)."*

- **Dosen**: *"Bagaimana kalian menangani pembagian nol (division by zero) pada formula ekonomi?"*
  - **Anda**: *"Di White-Box testing pada `EconomicFormulaTest`, kami menguji Basis Path untuk kecamatan yang kapasitasnya 0 atau data belum cukup, dengan fallback nilai 0 dan flag penanda statistik, sehingga mencegah sistem crash atau menghasilkan NaN (BUG-002)."*

- **Dosen**: *"Apakah sudah ada CI/CD?"*
  - **Anda**: *"Sudah, Pak. Di GitHub repositori kami ada `.github/workflows/ci.yml`. Setiap kali tim melakukan git push, GitHub Actions otomatis menjalankan build aset Vite dan eksekusi PHPUnit test."*

---
*Sukses untuk UTS besok! Tampil percaya diri dan tenang.*

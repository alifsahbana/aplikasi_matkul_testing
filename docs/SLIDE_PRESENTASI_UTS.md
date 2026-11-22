# DRAF SLIDE PRESENTASI & NASKAH BICARA (SCRIPT)
## Ujian Tengah Semester (UTS) - Phase 1 Audit SQA & Manpro
### Mata Kuliah: Manajemen dan Proyek Sistem Informasi (MKB020204)
**Program Studi S1 Sistem Informasi - Fakultas Teknik, Universitas Islam Madura (UIM)**  
**Dosen Pengampu Utama**: Aang Kisnu Darmawan, ST, MM  
**Topik Capstone**: WebGIS / EIS Potensi Ekonomi UMKM Tembakau Kabupaten Pamekasan  
**Alokasi Waktu**: 10–12 Menit Presentasi + Tanya Jawab  

---

```
================================================================================
SLIDE 1: JUDUL & IDENTITAS KELOMPOK
================================================================================
```

### [Tampilan Slide 1]
- **Judul**: Audit Penjaminan Mutu Perangkat Lunak (SQA) & Pengembangan Sistem Informasi Eksekutif WebGIS Potensi Ekonomi UMKM Tembakau Kabupaten Pamekasan
- **Sub-Judul**: Implementasi Standar ISO/IEC/IEEE 29119, V-Model, dan Automated Testing pada Komoditas Unggulan Madura
- **Program Studi**: S1 Sistem Informasi, Fakultas Teknik - Universitas Islam Madura (UIM)
- **Mata Kuliah**: Manajemen dan Proyek Sistem Informasi (UTS - Phase 1 SQA Audit)
- **Tim Pengembang**: [Nama Anda & Anggota Kelompok]
- **Dosen Pengampu**: Aang Kisnu Darmawan, ST, MM

---

### [Naskah Bicara Slide 1] (Durasi: ~1 Menit)
> *"Assalamu’alaikum Warahmatullahi Wabarakatuh.  
> Selamat pagi/siang kepada Bapak Aang Kisnu Darmawan, ST, MM dan rekan-rekan sekalian.  
> 
> Pada kesempatan Ujian Tengah Semester mata kuliah Manajemen dan Proyek Sistem Informasi hari ini, kami dari kelompok Capstone PjBL akan mempresentasikan hasil audit penjaminan mutu perangkat lunak (*Software Quality Assurance*) fase pertama pada proyek kami, yaitu: **WebGIS & EIS Potensi Ekonomi UMKM Tembakau Kabupaten Pamekasan**.  
> 
> Proyek ini kami bangun mengacu secara ketat pada RPS OBE, standar pengujian internasional ISO/IEC/IEEE 29119, serta metodologi V-Model. Hari ini kami tidak hanya menunjukkan antarmuka aplikasi, melainkan membuktikan secara faktual bagaimana setiap baris kode, batasan spasial geografis, formula ekonomi, dan manajemen cacat sistem telah diuji secara otomatis dan terverifikasi."*

---

```
================================================================================
SLIDE 2: LATAR BELAKANG & PROBLEM STATEMENT
================================================================================
```

### [Tampilan Slide 2]
- **Konteks Lokal**: Tembakau Madura sebagai komoditas strategis ekonomi Kabupaten Pamekasan yang tersebar di 13 kecamatan (Kadur, Pakong, Pegantenan, Larangan, dll).
- **Permasalahan Eksisting**:
  1. Persebaran lokasi UMKM, gudang, dan sentra olahan tembakau masih tercatat secara parsial dan manual.
  2. Belum ada visualisasi pemetaan spasial terintegrasi untuk analisis potensi ekonomi (utilitas kapasitas & klaster produksi).
  3. Rentan terjadi kesalahan data spasial (*human error*) saat petugas mendata koordinat di luar wilayah administratif Pamekasan.
- **Solusi yang Dibangun**: WebGIS terintegrasi dengan filter spasial reaktif, rute Google Maps, kontak langsung WhatsApp UMKM, serta perhitungan indeks ekonomi terukur berbasis kaidah SQA.

---

### [Naskah Bicara Slide 2] (Durasi: ~1.5 Menit)
> *"Bapak dan rekan-rekan, tembakau adalah urat nadi perekonomian petani dan UMKM di Madura, khususnya di Kabupaten Pamekasan. Namun, selama ini data sebaran sentra tembakau, kapasitas produksi riil, dan status keaktifan usaha masih berserakan dalam lembaran arsip fisik atau spreadsheet manual.  
> 
> Hal ini memicu dua masalah besar: pertama, pengambil kebijakan kesulitan melihat klaster wilayah dengan potensi tinggi vs daerah yang kapasitas produksinya belum optimal. Kedua, saat data diinput secara manual, rentan terjadi anomali data spasial—misalnya titik koordinat melompat ke tengah laut atau masuk ke kabupaten tetangga.  
> 
> Untuk itulah sistem WebGIS ini kami hadirkan, dilengkapi pagar validasi otomatis agar data yang masuk ke basis data selalu presisi dan dapat dipertanggungjawabkan secara ilmiah."*

---

```
================================================================================
SLIDE 3: LANDASAN SQA & ARSITEKTUR SISTEM (V-MODEL & ISO 29119)
================================================================================
```

### [Tampilan Slide 3]
- **Standar Rujukan**: ISO/IEC/IEEE 29119 (*Software and systems engineering — Software testing*).
- **Pendekatan Pengembangan**: **V-Model Framework**:
  - *Requirements Analysis* ⟷ *User Acceptance Testing (UAT)*
  - *System Architecture* ⟷ *System / E2E Testing (Playwright)*
  - *Module Design* ⟷ *Integration & API Testing (Postman/Newman)*
  - *Component Implementation* ⟷ *Unit Testing (PHPUnit - BVA/EP/CFG)*
- **Arsitektur Teknologi (3-Tier MVC)**:
  - *Presentation*: Tailwind CSS, Leaflet JS, Alpine.js, Responsive View.
  - *Application Logic*: Laravel 11 MVC, Spasial Geolocation Service, Economic Analysis Service.
  - *Data Layer*: SQLite In-Memory (Test Stage) / PostgreSQL PostGIS (Production Container).

---

### [Naskah Bicara Slide 3] (Durasi: ~1.5 Menit)
> *"Sesuai dengan target CPMK 1 mata kuliah ini, sistem kami dirancang dengan mengadopsi V-Model. Kami tidak menunggu aplikasi selesai 100% baru diuji, melainkan sejak perancangan kebutuhan fungsional, kami sudah memetakan pasangan pengujiannya.  
> 
> Pada tataran komponen dasar, fungsi kalkulasi dan koordinat kami uji dengan **Unit Testing**. Pada level antarmuka modul, kami uji dengan **Integration Testing** dan endpoint GeoJSON. Pada level alur pengguna, kami siapkan **E2E Playwright**, hingga puncaknya pada perancangan skenario **UAT Sign-off** bersama pemangku kepentingan dinas dan UMKM.  
> 
> Pemisahan arsitektur 3-tier ini memastikan kode modular, mudah diuji secara terisolasi, dan mematuhi prinsip Clean Architecture."*

---

```
================================================================================
SLIDE 4: PENGUJIAN BLACK-BOX – BOUNDARY VALUE ANALYSIS (BVA) & EP
================================================================================
```

### [Tampilan Slide 4]
- **Target Pengujian**: Form Input Koordinat Spasial WebGIS.
- **Batas Geografis Riil Kabupaten Pamekasan**:
  - Lintang (*Latitude*): `-7.3500000` s/d `-6.9500000`
  - Bujur (*Longitude*): `103.3500000` s/d `103.6500000`
- **Tabel Partisi & Nilai Uji (BVA & EP)**:
  1. *Min Boundary*: `-7.3500000, 103.3500000` ➔ **VALID (PASS)**
  2. *Max Boundary*: `-6.9500000, 103.6500000` ➔ **VALID (PASS)**
  3. *Centroid (Kota Pamekasan)*: `-7.1610000, 103.4800000` ➔ **VALID (PASS)**
  4. *Just Below Min Lat*: `-7.3500001` (Selat Madura) ➔ **DITOLAK (PASS)**
  5. *Just Above Max Lat*: `-6.9499999` (Laut Jawa) ➔ **DITOLAK (PASS)**
  6. *Equivalence Partition Invalid*: Koordinat Surabaya (`-7.2575, 112.7521`) ➔ **DITOLAK (PASS)**
- **Hasil**: 2 Test Cases, 12 Assertions lulus 100%.

---

### [Naskah Bicara Slide 4] (Durasi: ~2 Menit)
> *"Sekarang kita masuk ke inti metodologi pengujian Pekan 3, yaitu **Black-Box Testing dengan teknik Boundary Value Analysis dan Equivalence Partitioning**.  
> 
> Koordinat geografis Kabupaten Pamekasan memiliki batas matematis yang sangat definitif, yaitu Lintang minus 7.35 hingga minus 6.95, serta Bujur 103.35 hingga 103.65.  
> Melalui teknik BVA, kami tidak hanya menguji titik tengah kota, tetapi secara presisi menguji nilai tepi batas administratif, serta nilai minus epsilon atau plus epsilon satu angka di balik desimal.  
> 
> Melalui partisi ekivalensi (EP), jika petugas keliru memasukkan koordinat wilayah luar—misalnya koordinat Kota Surabaya atau koordinat null—sistem secara otomatis menggagalkan validasi pada tingkat server. Pengujian unit ini kami otomatiskan di `CoordinateValidationTest.php` dan terbukti berhasil menjaga keaslian data spasial."*

---

```
================================================================================
SLIDE 5: PENGUJIAN STRUCTURAL (WHITE-BOX) & LOGIKA BISNIS
================================================================================
```

### [Tampilan Slide 5]
- **Target Pengujian**: `EconomicAnalysisService.php` (Pekan 4 & 5 RPS).
- **Analisis White-Box (Control Flow Graph & Cyclomatic Complexity)**:
  - *Basis Path 1*: Kapasitas Terpasang > 0, Data Lengkap ➔ Hitung Rasio Utilitas Kapasitas Produksi.
  - *Basis Path 2*: Kapasitas Terpasang = 0 ➔ Penanganan Khusus *Zero-Division* (Fallback nilai 0, cegah crash `NaN`).
  - *Basis Path 3*: Jumlah data di bawah batas kuorum statistik ➔ Munculkan status proteksi `Data Belum Cukup`.
- **Decision Table Testing**:
  - Matriks kombinasi status UMKM (*Aktif*, *Musiman*, *Nonaktif*) dipadukan dengan volume transaksi tahunan.
- **Hasil PHPUnit**: Seluruh cabang percabangan (*branches*) ter-cover 100%.

---

### [Naskah Bicara Slide 5] (Durasi: ~1.5 Menit)
> *"Pada Pekan 4 dan 5, kami menerapkan **Structural atau White-Box Testing**. Kami membedah jalur logika program pada modul Analisis Potensi Ekonomi menggunakan diagram alur kendali (*Control Flow Graph*).  
> 
> Mengapa ini krusial? Pada sistem analitik, jika sebuah kecamatan baru belum memiliki data kapasitas produksi, pembagian angka dengan nol (*division by zero*) dapat merusak kalkulasi keseluruhan menjadi `NaN`.  
> Melalui pengujian Basis Path Coverage di `EconomicFormulaTest.php`, kami membuktikan bahwa setiap kemungkinan percabangan kode telah ditangani: sistem tidak akan melempar error, melainkan memberikan penanda statistik yang akurat bahwa data di wilayah tersebut masih dalam tahap pengumpulan."*

---

```
================================================================================
SLIDE 6: DEFECT MANAGEMENT & PERBAIKAN BUKTI NYATA (DEFECT LOG)
================================================================================
```

### [Tampilan Slide 6]
- **Standar Pencatatan**: Sesuai ISO 29119 Test Incident Report.
- **Statistik Penanganan Cacat**:
  - Total Defect Teridentifikasi: **9 Kasus (BUG-001 s/d BUG-009)**
  - Cacat Tingkat *Critical*: 1 / 1 (100% Resolved)
  - Cacat Tingkat *High*: 5 / 5 (100% Resolved)
  - Cacat Tingkat *Medium / Low*: 3 / 3 (100% Resolved)
- **Sorotan Cacat Penting yang Berhasil Diperbaiki**:
  - `BUG-005 (Critical)`: Koordinat Surabaya sempat lolos ke database ➔ *Fixed*: Pemasangan validasi spasial server-side.
  - `BUG-002 (Medium)`: Skor kalkulasi menampilkan `NaN` ➔ *Fixed*: Handling zero-division & kuorum.
  - `BUG-004 (High)`: Percobaan login tanpa batas (rentan brute-force) ➔ *Fixed*: Implementasi RateLimiter 5x percobaan per menit.
  - `BUG-009 (High)`: Masalah izin bootstrap PHPUnit di lingkungan Windows ➔ *Fixed*: Fallback pengecekan file exists.

---

### [Naskah Bicara Slide 6] (Durasi: ~1.5 Menit)
> *"Sesuai dengan arahan Bapak Aang Kisnu Darmawan dalam RPS, proses pengujian tidak boleh fiktif atau mengada-ada. Kami bangga menyampaikan bahwa kami mencatat setiap kendala nyata yang kami temui dalam **Defect Log**.  
> 
> Dari awal pengerjaan, kami mendeteksi 9 cacat perangkat lunak. Salah satu yang paling krusial adalah **BUG-005**, di mana pada versi awal, koordinat di luar Pamekasan masih bisa lolos jika diisi secara manual. Kami melakukan *Root Cause Analysis*, memperbaiki validasi di controller, dan menulis unit test khusus untuk mengunci agar bug tersebut tidak pernah muncul kembali.  
> Hari ini, rasio penyelesaian cacat kami adalah **100% Resolved**, membuktikan kedewasaan proses rekayasa perangkat lunak tim kami."*

---

```
================================================================================
SLIDE 7: LIVE DEMO & AUTOMATED TESTING VERIFICATION
================================================================================
```

### [Tampilan Slide 7]
- **Sesi Demonstrasi Langsung**:
  1. *WebGIS Public Interface*: Peta interaktif Leaflet, switcher basemap OSM CartoDB & Esri Satelit, filter kecamatan tembakau (Kadur, Pakong, Pegantenan), marker popup, rute Google Maps, tautan chat WhatsApp.
  2. *Negative Testing Demo*: Uji coba form penambahan UMKM dengan koordinat luar wilayah.
  3. *Automated Test Suite Execution*: Eksekusi langsung perintah PHPUnit di terminal.
- **Status Otomasi**: 24 Tests, 107 Assertions, 0 Failure, 0 Error (Execution Time: ~21 detik).

---

### [Naskah Bicara Slide 7 - Panduan Saat Demo] (Durasi: ~3 Menit)
> *(Pindah layar ke Browser `http://127.0.0.1:8000`)*  
> *"Izinkan kami mendemonstrasikan aplikasi secara langsung.  
> Di layar utama, Bapak dapat melihat antarmuka WebGIS Pamekasan. Peta ini memuat sebaran UMKM tembakau dengan clustering dinamis.  
> Jika kita ubah layer ke **Esri Satellite**, kita dapat melihat citra satelit kondisi geografis lahan.  
> Ketika kita filter Kecamatan **Kadur**—salah satu sentra tembakau terpenting—marker langsung terisolasi hanya pada wilayah tersebut. Setiap marker memuat informasi kapasitas produksi, nomor kontak petani via tombol WhatsApp, serta navigasi rute Google Maps secara instan.  
> 
> *(Pindah ke halaman Tambah UMKM)*  
> Sekarang kami tunjukkan pengujian negatif: jika saya memasukkan titik di Surabaya, sistem langsung menolak dan menampilkan pesan error bahwa koordinat berada di luar wilayah Kabupaten Pamekasan.  
> 
> *(Buka Terminal)*  
> Dan sebagai bukti puncak penjaminan mutu, kami eksekusi seluruh automated test suite:  
> `php vendor/phpunit/phpunit/phpunit --testdox`  
> *(Biarkan terminal berjalan dan menampilkan tulisan hijau OK 24 tests)*  
> Seluruh 24 skenario pengujian unit, integrasi, dan keamanan lulus seratus persen."*

---

```
================================================================================
SLIDE 8: KESIMPULAN & KESIAPAN CAPSTONE FASE 2 (MENUJU UAS)
================================================================================
```

### [Tampilan Slide 8]
- **Capaian Phase 1 (UTS - Bobot 25%)**:
  - Seluruh target Sub-CPMK 1 s/d Sub-CPMK 7 terpenuhi secara tuntas.
  - Dokumen artefak lengkap: *Test Plan*, *Test Case Matrix*, *Defect Log*, *Architecture Guide*.
  - Repositori aktif di GitHub dengan *Pipeline CI/CD Automated Build & Test*.
- **Rencana Phase 2 (Pekan 9 s/d 16 - Menuju UAS)**:
  - Pelaksanaan Uji Beban Konkurensi (*Load Testing k6*).
  - Simulasi Migrasi Data ETL dari data kotor UMKM tembakau.
  - Simulasi *Live Rollback* saat transisi *Cutover*.
- **Penutup**: Terima Kasih & Sesi Tanya Jawab.

---

### [Naskah Bicara Slide 8] (Durasi: ~1 Menit)
> *"Sebagai kesimpulan, pada Evaluasi Tengah Semester ini, kami telah menuntaskan seluruh target luaran fase pertama: mulai dari dokumen perencanaan, matriks kasus uji, manajemen cacat, hingga otomasi pengujian dan integrasi CI/CD di GitHub.  
> 
> Fondasi yang kokoh ini membuat kami sangat siap melangkah ke fase kedua menuju UAS, yaitu pengujian performa k6, migrasi data ETL, dan simulasi live rollback.  
> 
> Demikian presentasi dari kelompok kami. Kritik, saran, dan pertanyaan dari Bapak Aang Kisnu Darmawan sangat kami nantikan untuk penyempurnaan sistem ini.  
> Terima kasih. Wassalamu’alaikum Warahmatullahi Wabarakatuh."*

# Daftar Fitur & Matriks Kebutuhan Sistem

## 1. Modul Pengguna & Hak Akses

| ID | Fitur | Role | Deskripsi Fungsional |
|---|---|---|---|
| SEC-01 | Login Pengguna | Tamu / All | Otentikasi sesi menggunakan email dan kata sandi dengan perlindungan brute-force throttle. |
| SEC-02 | Logout Aman | All Terotentikasi | Mengakhiri sesi, invalidasi session token, dan regenerasi CSRF. |
| SEC-03 | Role-Based Access Control | Sistem | Middleware memisahkan akses Admin (Dashboard manajemen, CRUD, Impor) dan Pengunjung Publik. |

---

## 2. Modul Publik & Eksplorasi Spasial (WebGIS)

| ID | Fitur | Pengguna | Deskripsi Fungsional |
|---|---|---|---|
| GIS-01 | Peta Interaktif Leaflet | Pengunjung, Admin | Peta basemap OSM dengan kontrol zoom, full-screen, layer switcher, dan auto-center Kabupaten Pamekasan (-7.16°, 103.48°). |
| GIS-02 | Marker Clustering & Kategori | Pengunjung, Admin | Penanda lokasi berbasis koordinat presisi dengan warna dinamis sesuai kategori rantai pasok tembakau. |
| GIS-03 | Info Popup Interaktif | Pengunjung, Admin | Popup interaktif saat marker diklik: menampilkan Nama UMKM, Kategori, Desa/Kecamatan, Kapasitas Produksi, Tenaga Kerja, Status Usaha, dan Tombol Rincian. |
| GIS-04 | Filter Multi-Kriteria | Pengunjung, Admin | Filter dinamis instan tanpa reload (Alpine.js & Leaflet layer) berdasarkan Kecamatan dan Kategori Usaha. |
| GIS-05 | Pencarian Lokasi UMKM | Pengunjung, Admin | Kotak pencarian realtime dengan highlight marker otomatis dan fly-to koordinat terpilih. |
| GIS-06 | Layer Batas Wilayah Kecamatan | Pengunjung, Admin | Overlay poligon batas 13 kecamatan dari data GeoJSON dengan tooltip statistik kecamatan. |
| GIS-07 | Legenda Peta Dinamis | Pengunjung, Admin | Indikator visual warna marker dan simbol kategori rantai pasok tembakau. |

---

## 3. Modul Dashboard & Analisis Potensi Ekonomi

| ID | Fitur | Pengguna | Deskripsi Fungsional |
|---|---|---|---|
| DSH-01 | Metrik Ringkasan Utama | Pengunjung, Admin | Kartu ringkasan total UMKM terdaftar, UMKM dengan koordinat valid, total kapasitas produksi, total tenaga kerja, dan nilai perputaran ekonomi. |
| DSH-02 | Grafik Distribusi Kecamatan | Pengunjung, Admin | Diagram batang (Chart.js) menampilkan peringkat jumlah UMKM per kecamatan. |
| DSH-03 | Grafik Komposisi Kategori | Pengunjung, Admin | Diagram donat (Chart.js) komposisi rantai pasok dari petani hingga produsen rokok linting. |
| ECO-01 | Analisis Utilisasi Kapasitas | Pengunjung, Admin | Rasio produksi riil terhadap kapasitas teoritis per kecamatan (Persentase Efisiensi Panen/Produksi). |
| ECO-02 | Indeks Potensi Ekonomi | Pengunjung, Admin | Skor potensi komposit terbobot berdasarkan 4 indikator terukur (Kapasitas 30%, Tenaga Kerja 25%, Nilai Transaksi 25%, Diversifikasi 20%). |
| ECO-03 | Status "Data Belum Cukup" | Pengunjung, Admin | Penanganan analitik bertanggung jawab: sistem menolak menarik kesimpulan jika data kecamatan belum memenuhi kuorum validitas statistik. |
| ECO-04 | Rekomendasi Kebijakan Terarah | Pengunjung, Admin | Rekomendasi otomatis berbasis aturan ilmiah (contoh: bantuan mekanisasi pada rasio utilisasi rendah, kemitraan pada klaster pembibitan). |

---

## 4. Modul Manajemen Data UMKM (Admin)

| ID | Fitur | Pengguna | Deskripsi Fungsional |
|---|---|---|---|
| CRUD-01 | Pencatatan Data UMKM Baru | Admin | Form input dengan validasi koordinat batas geografis Pamekasan, nama, kategori, kecamatan, kapasitas produksi, dan kontak. |
| CRUD-02 | Pembaruan & Koreksi Data | Admin | Form edit data dengan pengisian koordinat interaktif (klik pada mini-map form). |
| CRUD-03 | Penghapusan Data | Admin | Konfirmasi hapus modal untuk mencegah ketidaksengajaan. |
| CRUD-04 | Validasi Lintang & Bujur | Sistem | Validasi rentang Latitude (-7.35 s/d -6.95) dan Longitude (103.35 s/d 103.65). |

---

## 5. Modul Impor & Ekspor Data

| ID | Fitur | Pengguna | Deskripsi Fungsional |
|---|---|---|---|
| IMP-01 | Unduh Template CSV Demo | Admin | Penyediaan file template CSV standar dengan contoh data sintetis berlabel eksplisit. |
| IMP-02 | Pratinjau Berkas CSV | Admin | Menampilkan 5 baris pertama data sebelum diproses untuk verifikasi pemetaan kolom. |
| IMP-03 | Eksekusi Impor Transaksional | Admin | Proses impor dalam `DB::transaction`; rollback otomatis jika terdapat fatal error. |
| IMP-04 | Laporan Hasil Impor & Defect Log | Admin | Menampilkan rincian baris sukses, baris duplikat yang dilewati, dan baris gagal beserta alasan error per baris. |
| EXP-01 | Ekspor Laporan CSV/Excel | Admin, Pengunjung | Ekspor data tersaring (per kecamatan/kategori) dengan masking data privat pemilik jika diakses publik. |

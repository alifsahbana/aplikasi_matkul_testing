# Dokumen Uji Penerimaan Pengguna (User Acceptance Testing - UAT)

## 1. Informasi Proyek
- **Nama Sistem**: WebGIS UMKM Tembakau Pamekasan
- **Versi UAT**: 1.0.0
- **Penguji**: Mahasiswa & Dosen Pengampu Mata Kuliah MASI & SQA
- **Tanggal Pelaksanaan**: Oktober 2026

## 2. Kriteria Penerimaan (Acceptance Criteria)
Agar sistem dinyatakan **DITERIMA (Accepted)** secara resmi, seluruh kriteria wajib berikut harus terpenuhi:

| No | Kriteria Penerimaan | Target / Tolok Ukur | Status Kelulusan |
|---|---|---|---|
| AC-01 | Semua fitur CRUD UMKM berfungsi tanpa error fatal | 100% operasi sukses | ✅ Lulus |
| AC-02 | Peta WebGIS menampilkan marker sesuai dengan koordinat geospasial valid di Pamekasan | Marker muncul tepat pada lokasi | ✅ Lulus |
| AC-03 | Filter peta berdasarkan kecamatan dan kategori usaha bekerja real-time | Hasil filter akurat | ✅ Lulus |
| AC-04 | Impor CSV memvalidasi batas geografis (Lintang [-7.35 s/d -6.95], Bujur [103.35 s/d 103.65]) | Baris gagal tercatat dengan jelas | ✅ Lulus |
| AC-05 | Halaman Analisis menyajikan skor potensi ekonomi berbasis fakta dari database | Nilai skor terhitung otomatis | ✅ Lulus |
| AC-06 | Modul otorisasi mencegah pengguna biasa mengakses `/admin` | Redirect ke login atau 403 | ✅ Lulus |
| AC-07 | Tidak ada bug Critical atau High yang terbuka saat demo akhir | Zero critical/high defects | ✅ Lulus |

## 3. Skenario UAT

### Skenario 1: Eksplorasi Peta Publik
**Langkah-langkah:**
1. Buka browser dan akses URL aplikasi (`/`).
2. Klik menu "Peta Interaktif".
3. Pilih filter Kecamatan "Kadur".
4. Amati marker yang muncul di peta.
5. Klik salah satu marker untuk melihat popup profil UMKM.

**Hasil Diharapkan:** Peta terpusat di Pamekasan, marker berlabel sesuai kategori, popup menampilkan nama dan kapasitas produksi.

---

### Skenario 2: Pengelolaan Data UMKM (Admin)
**Langkah-langkah:**
1. Login sebagai admin menggunakan kredensial resmi.
2. Buka menu "Data UMKM".
3. Klik "Tambah UMKM Baru".
4. Masukkan data lengkap termasuk pilih kecamatan dan kategori.
5. Pilih koordinat dengan klik di mini-map form.
6. Simpan.

**Hasil Diharapkan:** Data tersimpan, notifikasi sukses muncul, data baru terlihat di tabel.

---

### Skenario 3: Impor Data Massal
**Langkah-langkah:**
1. Login sebagai admin.
2. Buka menu "Impor CSV".
3. Unduh template DEMO CSV.
4. Ubah beberapa baris data pada berkas.
5. Unggah kembali ke sistem dan klik "Eksekusi Impor".

**Hasil Diharapkan:** Tampilan pratinjau 5 baris muncul, proses selesai, laporan jumlah sukses dan gagal ditampilkan.

---

### Skenario 4: Analisis Potensi Ekonomi
**Langkah-langkah:**
1. Login sebagai admin.
2. Buka menu "Analisis Potensi Ekonomi".
3. Periksa peringkat kecamatan dan skor komposit.

**Hasil Diharapkan:** Tabel peringkat tampil dengan pemisahan Facts, Estimasi, dan Rekomendasi yang jelas. Kecamatan dengan data kurang mendapat status "Data Belum Cukup".

## 4. Tanda Tangan Persetujuan (Sign-off)

| Peran | Nama | Tanda Tangan | Tanggal |
|---|---|---|---|
| Mahasiswa Penanggung Jawab | __________________ | __________________ | ____/____/2026 |
| Dosen Pembimbing | __________________ | __________________ | ____/____/2026 |
| Reviewer SQA | __________________ | __________________ | ____/____/2026 |

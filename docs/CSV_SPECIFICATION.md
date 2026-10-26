# Dokumentasi Format & Spesifikasi Impor CSV Data UMKM Tembakau

## 1. Ikhtisar
Modul impor CSV pada aplikasi **WebGIS UMKM Tembakau Pamekasan** dirancang untuk memudahkan migrasi data massal dari survei lapangan, instansi dinas koperasi, maupun asosiasi petani tembakau.

Seluruh proses impor dijalankan dalam transaksi basis data (`DB::transaction`) dengan validasi spasial, tipe data, serta integritas relasional ke tabel referensi `districts` dan `categories`.

---

## 2. Spesifikasi Kolom Berkas CSV

| No | Nama Kolom Header | Tipe Data | Wajib | Aturan & Batasan Validasi | Contoh Nilai |
|---|---|---|---|---|---|
| 1 | `kode_umkm` | String | Ya | Unik, maks 50 karakter. Ditolak jika sudah terdaftar. | `DEMO-PMK-101` |
| 2 | `nama_umkm` | String | Ya | Nama resmi unit usaha / kelompok tani, maks 200 karakter. | `Sentra Rajangan Barurambat` |
| 3 | `nama_pemilik` | String | Tidak | Nama pemilik / penanggung jawab. | `Ahmad Subandi` |
| 4 | `kategori` | String | Ya | Harus cocok dengan 6 kategori rantai pasok tembakau resmi. | `Pengeringan & Pengolahan Daun` |
| 5 | `kecamatan` | String | Ya | Harus cocok dengan 13 kecamatan resmi Kabupaten Pamekasan. | `Pamekasan` |
| 6 | `desa` | String | Tidak | Nama desa / kelurahan administratif. | `Barurambat Timur` |
| 7 | `alamat` | String | Tidak | Alamat detail lokasi fisik tempat usaha. | `Jl. Veteran No. 12` |
| 8 | `latitude` | Decimal | Ya | Nilai lintang. Wajib berada pada rentang **[-7.3500000 s/d -6.9500000]**. | `-7.1590000` |
| 9 | `longitude` | Decimal | Ya | Nilai bujur. Wajib berada pada rentang **[103.3500000 s/d 103.6500000]**. | `103.4850000` |
| 10 | `luas_lahan_ha` | Numeric | Tidak | Luas lahan budidaya / bangunan dalam satuan hektar (min 0). | `0.45` |
| 11 | `kapasitas_produksi` | Numeric | Tidak | Kapasitas produksi potensial (min 0). | `8000` |
| 12 | `satuan_produksi` | String | Tidak | Satuan kuantitas (`Kg`, `Ton`, `Bal`, `Batang`, `Liter`). | `Kg` |
| 13 | `produksi_aktual` | Numeric | Tidak | Volume produksi nyata yang dihasilkan dalam periode tercatat. | `7200` |
| 14 | `periode_produksi` | String | Tidak | Periode waktu (`Musim 2026`, `Bulanan`, `Tahunan`). | `Musim 2026` |
| 15 | `jumlah_tenaga_kerja` | Integer | Tidak | Tenaga kerja lokal yang terserap (min 0). | `14` |
| 16 | `nilai_transaksi` | Numeric | Tidak | Estimasi omzet / nilai transaksi perputaran uang dalam Rupiah. | `450000000` |
| 17 | `status_usaha` | String | Tidak | Pilihan status: `aktif`, `musiman`, `non-aktif`. Default: `aktif`. | `aktif` |
| 18 | `kontak` | String | Tidak | Nomor telepon atau WhatsApp publik UMKM. | `081234567891` |

---

## 3. Daftar Nilai Referensi Valid

### 13 Kecamatan Resmi Kabupaten Pamekasan
`Tlanakan`, `Pademawu`, `Galis`, `Pamekasan`, `Proppo`, `Palengaan`, `Pegantenan`, `Larangan`, `Pakong`, `Kadur`, `Waru`, `Batumarmar`, `Pasean`.

### 6 Kategori Rantai Pasok Tembakau Resmi
1. `Petani & Pembibitan Tembakau`
2. `Pengeringan & Pengolahan Daun`
3. `Pengepul & Perdagangan Rajangan`
4. `Produsen Sigaret Kretek Tangan (SKT)`
5. `Industri Rokok Skala Kecil`
6. `Produk Olahan & Turunan Tembakau`

---

## 4. Penanganan Kesalahan (Error Handling)
1. **Kegagalan Validasi Koordinat**: Baris dengan koordinat di luar jangkauan geografis Pamekasan akan ditandai gagal dengan catatan detail nomor baris dan nilai lintang/bujur yang melanggar.
2. **Duplikasi Data**: Baris dengan kode UMKM yang telah ada di database akan dilewati dan dilaporkan pada log impor.
3. **Audit Trail**: Setiap proses impor menghasilkan rekaman di tabel `import_logs` yang mencatat jumlah baris sukses, baris gagal, detail error JSON, nama pengguna pengunggah, dan nama berkas asli.

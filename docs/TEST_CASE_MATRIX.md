# Matriks Kasus Uji (Test Case Matrix)

Berikut adalah rekapitulasi seluruh kasus uji fungsional, non-fungsional, dan spasial yang telah diimplementasikan dan diverifikasi:

## 1. Modul Autentikasi & Otorisasi Pengguna

| ID Kasus Uji | Modul       | Teknik Uji      | Deskripsi Kasus Uji             | Input Data                                                  | Hasil yang Diharapkan                                        | Hasil Aktual                            | Status   |
| ------------ | ----------- | --------------- | ------------------------------- | ----------------------------------------------------------- | ------------------------------------------------------------ | --------------------------------------- | -------- |
| TC-AUTH-001  | Autentikasi | Functional      | Menampilkan formulir login      | Request GET `/login`                                        | HTTP 200, form login tampil dengan tombol quick-fill         | HTTP 200, form tampil lengkap           | **PASS** |
| TC-AUTH-002  | Autentikasi | Functional      | Login berhasil role Admin       | Email: `admin@pamekasan-gis.id`, Pass: `admin12345`         | Redirect ke `/admin`, sesi terotentikasi sebagai Admin       | Redirect `/admin`, status authenticated | **PASS** |
| TC-AUTH-003  | Autentikasi | Functional      | Login berhasil role Pengunjung  | Email: `pengunjung@pamekasan-gis.id`, Pass: `pengunjung123` | Redirect ke `/`, sesi terotentikasi sebagai Pengunjung       | Redirect `/`, status authenticated      | **PASS** |
| TC-AUTH-004  | Autentikasi | Negative / EP   | Login dengan kata sandi salah   | Email valid, Password: `wrong_pass`                         | Login gagal, kembali ke form dengan pesan error pada session | Login ditolak, pesan validasi muncul    | **PASS** |
| TC-AUTH-005  | Otorisasi   | Security / RBAC | Pengunjung mengakses URL admin  | Request GET `/admin` dengan akun role `pengunjung`          | Akses ditolak dengan kode status HTTP 403 Forbidden          | HTTP 403 Forbidden                      | **PASS** |
| TC-AUTH-006  | Otorisasi   | Security / RBAC | Tamu tanpa sesi mengakses admin | Request GET `/admin` tanpa cookie sesi                      | Dialihkan (HTTP 302) ke halaman `/login`                     | Redirect HTTP 302 ke `/login`           | **PASS** |
| TC-AUTH-007  | Autentikasi | Functional      | Logout pengguna                 | Request POST `/logout` dengan CSRF token valid              | Sesi dihapus, CSRF token diregenerasi, redirect ke `/`       | Sesi terhapus, redirect ke `/`          | **PASS** |

---

## 2. Modul Validasi Geospasial & Boundary Value Analysis (BVA)

| ID Kasus Uji | Modul   | Teknik Uji               | Nilai Lintang (Lat) | Nilai Bujur (Lng) | Hasil yang Diharapkan                          | Hasil Aktual                   | Status   |
| ------------ | ------- | ------------------------ | ------------------- | ----------------- | ---------------------------------------------- | ------------------------------ | -------- |
| TC-BVA-001   | Spasial | BVA (Min Edge)           | `-7.3500000`        | `103.3500000`     | Valid (Titik pojok batas barat daya Pamekasan) | Validasi Mengembalikan `true`  | **PASS** |
| TC-BVA-002   | Spasial | BVA (Max Edge)           | `-6.9500000`        | `103.6500000`     | Valid (Titik pojok batas timur laut Pamekasan) | Validasi Mengembalikan `true`  | **PASS** |
| TC-BVA-003   | Spasial | BVA (Centroid)           | `-7.1610000`        | `103.4800000`     | Valid (Titik tengah pusat kota Pamekasan)      | Validasi Mengembalikan `true`  | **PASS** |
| TC-BVA-004   | Spasial | BVA (Just Below Min Lat) | `-7.3500001`        | `103.4800000`     | Tidak Valid (Sedikit di luar batas selatan)    | Validasi Mengembalikan `false` | **PASS** |
| TC-BVA-005   | Spasial | BVA (Just Above Max Lat) | `-6.9499999`        | `103.4800000`     | Tidak Valid (Sedikit di luar batas utara)      | Validasi Mengembalikan `false` | **PASS** |
| TC-BVA-006   | Spasial | BVA (Just Below Min Lng) | `-7.1610000`        | `103.3499999`     | Tidak Valid (Sedikit di luar batas barat)      | Validasi Mengembalikan `false` | **PASS** |
| TC-BVA-007   | Spasial | BVA (Just Above Max Lng) | `-7.1610000`        | `103.6500001`     | Tidak Valid (Sedikit di luar batas timur)      | Validasi Mengembalikan `false` | **PASS** |
| TC-EP-008    | Spasial | EP (Kota Lain)           | `-7.2575000`        | `112.7521000`     | Tidak Valid (Koordinat Kota Surabaya)          | Validasi Mengembalikan `false` | **PASS** |
| TC-EP-009    | Spasial | EP (Null Value)          | `null`              | `103.4800000`     | Tidak Valid (Nilai lintang kosong/null)        | Validasi Mengembalikan `false` | **PASS** |

---

## 3. Modul CRUD Data UMKM (Admin)

| ID Kasus Uji | Modul     | Teknik Uji         | Deskripsi Kasus Uji                      | Input Data                                       | Hasil yang Diharapkan                                      | Hasil Aktual                           | Status   |
| ------------ | --------- | ------------------ | ---------------------------------------- | ------------------------------------------------ | ---------------------------------------------------------- | -------------------------------------- | -------- |
| TC-CRUD-001  | CRUD UMKM | Functional         | Admin melihat daftar UMKM                | GET `/admin/umkm`                                | HTTP 200, tabel master memuat daftar data dan pagination   | HTTP 200, tabel berhasil dirender      | **PASS** |
| TC-CRUD-002  | CRUD UMKM | Functional         | Menambahkan UMKM koordinat valid         | Form data UMKM valid di Kecamatan Tlanakan       | Data tersimpan di basis data, flash notification sukses    | Data tersimpan di DB, redirect sukses  | **PASS** |
| TC-CRUD-003  | CRUD UMKM | Negative / Spasial | Menolak penambahan di luar batas wilayah | Form data dengan Lat: `-7.5000`, Lng: `112.7521` | Ditolak oleh server validator, error ditampilkan pada form | Ditolak dengan pesan validasi spasial  | **PASS** |
| TC-CRUD-004  | CRUD UMKM | Functional         | Memperbarui data UMKM eksis              | PUT `/admin/umkm/{id}` data baru                 | Data di tabel `tobacco_umkms` terupdate sesuai input baru  | Nilai terupdate di DB, redirect sukses | **PASS** |
| TC-CRUD-005  | CRUD UMKM | Functional         | Menghapus data UMKM                      | DELETE `/admin/umkm/{id}`                        | Data terhapus dari basis data, tidak muncul di tabel       | Record terhapus dari tabel database    | **PASS** |

---

## 4. Modul WebGIS & Endpoint GeoJSON Spasial

| ID Kasus Uji | Modul       | Teknik Uji           | Deskripsi Kasus Uji                | Endpoint                               | Hasil yang Diharapkan                                      | Hasil Aktual                           | Status   |
| ------------ | ----------- | -------------------- | ---------------------------------- | -------------------------------------- | ---------------------------------------------------------- | -------------------------------------- | -------- |
| TC-GIS-001   | WebGIS      | Functional           | Akses halaman peta interaktif      | GET `/peta`                            | HTTP 200, kontainer Leaflet `#map` terinisialisasi         | HTTP 200, Leaflet aktif                | **PASS** |
| TC-GIS-002   | API GeoJSON | Functional           | API GeoJSON seluruh UMKM           | GET `/api/geojson/umkms`               | Format GeoJSON FeatureCollection dengan seluruh titik UMKM | FeatureCollection valid, array lengkap | **PASS** |
| TC-GIS-003   | API GeoJSON | Filter / Integration | Filter GeoJSON per kecamatan       | GET `/api/geojson/umkms?district_id=X` | Hanya mengembalikan UMKM pada kecamatan X                  | Hanya UMKM kecamatan X yang muncul     | **PASS** |
| TC-GIS-004   | API GeoJSON | Filter / Integration | Filter GeoJSON per kategori        | GET `/api/geojson/umkms?category_id=Y` | Hanya mengembalikan UMKM pada kategori Y                   | Hanya UMKM kategori Y yang muncul      | **PASS** |
| TC-GIS-005   | API GeoJSON | Search               | Pencarian realtime kata kunci nama | GET `/api/geojson/umkms?q=Kretek`      | Mengembalikan UMKM yang cocok dengan teks pencarian        | Data yang relevan dikembalikan         | **PASS** |
| TC-GIS-006   | API GeoJSON | Poligon              | API batas wilayah kecamatan        | GET `/api/geojson/districts`           | Mengembalikan 13 poligon kecamatan bergeometri GeoJSON     | 13 fitur poligon kecamatan valid       | **PASS** |

---

## 5. Modul Impor CSV & Transaksi Basis Data

| ID Kasus Uji | Modul     | Teknik Uji     | Deskripsi Kasus Uji                | Input Data                                        | Hasil yang Diharapkan                                  | Hasil Aktual                            | Status   |
| ------------ | --------- | -------------- | ---------------------------------- | ------------------------------------------------- | ------------------------------------------------------ | --------------------------------------- | -------- |
| TC-IMP-001   | Impor CSV | Functional     | Mengunduh template CSV demo        | GET `/admin/import/template`                      | Berkas CSV standar dengan data sintetis terunduh       | Berkas CSV demo terunduh                | **PASS** |
| TC-IMP-002   | Impor CSV | Integration    | Impor berkas CSV valid             | Berkas CSV dengan baris koordinat Pamekasan valid | Data tersimpan di DB, log impor berstatus `success`    | Data tersimpan, log berstatus `success` | **PASS** |
| TC-IMP-003   | Impor CSV | Error Handling | Impor baris dengan koordinat salah | Berkas CSV berisi koordinat di luar Pamekasan     | Baris salah ditolak, dicatat pada defect JSON log      | Baris ditolak, log berstatus `failed`   | **PASS** |
| TC-IMP-004   | Impor CSV | Business Rule  | Deteksi duplikasi kode UMKM        | Berkas CSV dengan kode yang telah ada di database | Baris duplikat dilewati dan dicatat sebagai gagal/skip | Baris duplikat dilewati secara aman     | **PASS** |

---

## 6. Modul Analisis Potensi Ekonomi & Decision Table

| ID Kasus Uji | Kondisi 1 (Jumlah UMKM) | Kondisi 2 (Kapasitas Tercatat) | Hasil Evaluasi Status           | Hasil Rekomendasi Sistem                                                | Status Uji |
| ------------ | ----------------------- | ------------------------------ | ------------------------------- | ----------------------------------------------------------------------- | ---------- |
| TC-ECO-001   | $\ge 2$ UMKM            | Valid ($> 0$)                  | **Memadai** (Skor CPI dihitung) | Rekomendasi terarah berbasis utilisasi & diversifikasi rantai pasok     | **PASS**   |
| TC-ECO-002   | $< 2$ UMKM              | Data minim                     | **Data Belum Cukup** (Skor = 0) | Sistem menolak membuat kesimpulan; mengusulkan survei lapangan tambahan | **PASS**   |

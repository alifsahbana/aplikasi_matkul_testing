# Entity Relationship Diagram (ERD) & Skema Basis Data

## 1. Visualisasi Diagram Entitas (Mermaid ERD)

```mermaid
erDiagram
    USERS ||--o{ IMPORT_LOGS : "melakukan"
    USERS ||--o{ TOBACCO_UMKMS : "mencatat/memverifikasi"
    DISTRICTS ||--o{ TOBACCO_UMKMS : "memiliki"
    CATEGORIES ||--o{ TOBACCO_UMKMS : "mengklasifikasikan"

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        string role "admin | pengunjung"
        timestamp email_verified_at
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    DISTRICTS {
        bigint id PK
        string code UK "Kode administratif kecamatan"
        string name "Nama kecamatan (contoh: Pamekasan, Kadur)"
        decimal latitude "Titik pusat lintang"
        decimal longitude "Titik pusat bujur"
        decimal area_km2 "Luas wilayah km²"
        jsonb geojson_boundary "Poligon batas wilayah kecamatan"
        text description
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        bigint id PK
        string name "Nama kategori rantai nilai tembakau"
        string slug UK
        string marker_color "Kode warna heksadesimal marker"
        string icon "Icon penanda rantai pasok"
        text description
        timestamp created_at
        timestamp updated_at
    }

    TOBACCO_UMKMS {
        bigint id PK
        string code UK "Kode unik registrasi UMKM"
        string name "Nama usaha UMKM"
        string owner_name "Nama pemilik (dapat disamarkan bila anonim)"
        bigint category_id FK
        bigint district_id FK
        string address "Alamat spesifik"
        string village "Desa / Kelurahan"
        decimal latitude "Koordinat lintang [-7.00 s.d -7.35]"
        decimal longitude "Koordinat bujur [103.35 s.d 103.65]"
        decimal land_area_ha "Luas lahan binaan / tanam (hektar)"
        decimal production_capacity "Kapasitas produksi potensial"
        string production_unit "Satuan (kg, ton, bal, batang)"
        decimal actual_production "Produksi riil tercatat"
        string production_period "Periode panen / siklus produksi"
        int worker_count "Jumlah tenaga kerja terserap"
        decimal transaction_value "Estimasi nilai perputaran uang (IDR)"
        string business_status "aktif | musiman | non-aktif"
        string data_source "Dinas Koperasi / Asosiasi / Survei Mandiri"
        string contact_phone "Kontak publik usaha"
        boolean is_verified "Status verifikasi data"
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }

    IMPORT_LOGS {
        bigint id PK
        bigint user_id FK
        string original_filename
        int total_rows
        int success_rows
        int failed_rows
        jsonb error_details "Detail baris dan jenis error"
        string status "completed | partially_failed | failed"
        timestamp created_at
        timestamp updated_at
    }
```

---

## 2. Rincian Kamus Data (Data Dictionary)

### A. Tabel `districts` (Kecamatan di Kabupaten Pamekasan)
Mencakup 13 kecamatan resmi:
1. Pamekasan (Kota)
2. Tlanakan
3. Pademawu
4. Galis
5. Larangan
6. Kadur
7. Pakong
8. Pegantenan
9. Palengaan
10. Proppo
11. Waru
12. Batumarmar
13. Pasean

- **id**: Primary Key (BIGSERIAL).
- **code**: Kode unik BPS/Kemendagri (VARCHAR 10, Unique).
- **name**: Nama kecamatan (VARCHAR 100).
- **latitude**: Koordinat centroid lintang (DECIMAL 10, 7).
- **longitude**: Koordinat centroid bujur (DECIMAL 10, 7).
- **area_km2**: Luas wilayah darat (DECIMAL 8, 2).
- **geojson_boundary**: Geometri poligon perbatasan kecamatan (JSONB / PostGIS GEOMETRY jika aktif).

### B. Tabel `categories` (Rantai Pasok Tembakau)
Klasifikasi rantai pasok industri tembakau:
1. `Petani & Pembibitan Tembakau` (Warna Marker: `#15803d` - Hijau Tua)
2. `Pengeringan & Pengolahan Daun` (Warna Marker: `#ca8a04` - Kuning Emas)
3. `Pengepul & Perdagangan Rajangan` (Warna Marker: `#ea580c` - Oranye Terakota)
4. `Produsen Sigaret Kretek Tangan (SKT)` (Warna Marker: `#dc2626` - Merah Tua)
5. `Industri Rokok Skala Kecil` (Warna Marker: `#7c3aed` - Ungu)
6. `Produk Olahan & Turunan Tembakau` (Warna Marker: `#0284c7` - Biru Laut)

### C. Tabel `tobacco_umkms` (Data Inti UMKM)
- Memiliki indeks komposit pada `(district_id, category_id)` untuk akselerasi query filter peta dan agregasi dashboard.
- Memiliki batasan spasial validasi: Latitude Pamekasan berada pada rentang `-7.3500000` hingga `-6.9500000`, dan Longitude berada pada rentang `103.3500000` hingga `103.6500000`.

---

## 3. Prinsip Normalisasi & Integritas Data
1. **Third Normal Form (3NF)**: Tidak ada redudansi transitif. Kategori dan Kecamatan dinormalisasi terpisah dengan Foreign Key berelasi `ON DELETE RESTRICT` untuk mencegah hilangnya data spasial secara tidak sengaja.
2. **PostGIS Compatibility**: Kolom `latitude` dan `longitude` bertipe `DECIMAL(10, 7)` yang secara alami portabel di SQLite/MySQL dan dapat dikonversi ke `ST_Point(longitude, latitude, 4326)` di PostgreSQL/PostGIS.
3. **Audit Trail**: Disertakan kolom `data_source`, `created_by`, dan timestamps untuk transparansi asal data perkuliahan.

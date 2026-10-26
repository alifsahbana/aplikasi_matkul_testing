# Rencana Rollback Rilis Aplikasi & Migrasi Database

> **Prinsip Utama**: Rollback kode aplikasi (Git/Docker) **TIDAK** otomatis membatalkan perubahan struktur database. Pemulihan database memerlukan prosedur terpisah dan aman.

## 1. Strategi Rollback Kode Aplikasi (Application Code)
### Git Version Control
```bash
# 1. Identifikasi tag/branch rilis terakhir stabil
git tag -l "v1.*.0" | sort -V | tail -1
# Contoh output: v1.2.0

# 2. Checkout ke tag stabil
git checkout v1.2.0

# 3. Rebuild Docker image
docker compose build --no-cache app
docker compose up -d app
```

### Docker Image Tagging
- Setiap rilis production wajib diberi tag semantik: `webgis-tembakau:v1.2.0`, `webgis-tembakau:v1.2.1`
- Image `latest` **hanya** digunakan untuk development, **dilarang** untuk production.

---

## 2. Prosedur Rollback Database (Database Migration Rollback)
### A. Rollback Migrasi Laravel Terakhir (Hanya Schema, TIDAK Data)
```bash
# Di dalam container aplikasi
docker compose exec app php artisan migrate:rollback --step=1

# Atau rollback semua migrasi spesifik (hati-hati!)
docker compose exec app php artisan migrate:reset --path=/database/migrations/2026_10_15_*.php
```

### B. Restore Database dari Backup SQL (Full Recovery - Termasuk Data)
> **Ini adalah cara paling aman** untuk mengembalikan kondisi sistem ke titik waktu sebelumnya.

1. Hentikan aplikasi untuk mencegah write:
```bash
docker compose stop app
```

2. Restore dump SQL PostgreSQL:
```bash
# Asumsi file backup: backup_20261015_140000.sql
cat backup_20261015_140000.sql | docker exec -i webgis-db psql -U postgres -d webgis_tembakau
```

3. Mulai ulang aplikasi:
```bash
docker compose start app
docker compose logs -f app
```

---

## 3. Skenario Rollback Terstruktur

| Skenario | Trigger | Aksi Rollback | Estimasi Waktu |
|---|---|---|---|
| **Bug Critical di Produksi** | Error 500 pada >5% request | Rollback Docker image ke tag sebelumnya + Restore DB dari backup terakhir | 5-10 menit |
| **Migrasi Schema Gagal** | `php artisan migrate` error di production | `php artisan migrate:rollback` + Cek log error | 2-5 menit |
| **Data Corrupt/Injeksi** | Data aneh ditemukan (contoh: negative capacity) | Restore DB dari backup sebelum insiden + Cek audit log `import_logs` | 10-15 menit |

---

## 4. Checklist Post-Rollback
- [ ] Verifikasi health check endpoint `/up` kembali 200 OK
- [ ] Tes manual 1 skenario CRUD UMKM
- [ ] Tes 1 request GeoJSON `/api/geojson/umkms`
- [ ] Pastikan tidak ada queue job stuck di tabel `jobs`
- [ ] Lapor hasil rollback ke log insiden (PIR)

---

## 5. Larangan Mutlak
- **JANGAN** menjalankan `php artisan migrate:fresh` di server production.
- **JANGAN** menghapus container database tanpa backup terverifikasi terlebih dahulu.
- **JANGAN** mengandalkan `git revert` saja untuk masalah migrasi database.

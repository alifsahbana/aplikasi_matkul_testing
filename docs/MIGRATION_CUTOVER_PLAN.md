# Strategi Migrasi, Cutover, dan Rollback Sistem WebGIS Pamekasan

## 1. Fase Persiapan Migrasi
### A. Audit Database Saat Ini
1. Ekspor data lokal SQLite (`database.sqlite`) sebagai backup arsip statis.
2. Dokumentasikan struktur relasional tabel `districts`, `categories`, `tobacco_umkms`, dan `import_logs`.

### B. Persiapan Server Produksi (VPS Docker)
1. Deploy image PostgreSQL 15 dengan ekstensi PostGIS menggunakan Docker Compose.
2. Gunakan file `.env.production` yang telah dirahasiakan kuncinya (`APP_KEY`, `DB_PASSWORD`).
3. Terapkan firewall rule hanya mengizinkan akses port 80 (HTTP) dan 443 (HTTPS) dari IP publik mahasiswa/dosen.

---

## 2. Strategi Migrasi Data (Blue-Green Strategy)
Menerapkan strategi **Blue-Green Deployment** untuk meminimalkan downtime:

1. **Fase Blue (Sistem Lama)**: Jalankan aplikasi versi stabil pertama pada container utama.
2. **Fase Green (Sistem Baru)**: Deploy container baru dengan migrasi database terbaru. Jalankan seeders DEMO data tembakau Pamekasan pada database Green.
3. **Validasi Integrasi**: Uji seluruh endpoint API GeoJSON dan form CRUD di container Green tanpa mengarahkan traffic publik.
4. **Cutover**: Alihkan DNS/Reverse Proxy (Nginx) dari Blue ke Green dalam satu langkah. Waktu henti (downtime) diperkirakan kurang dari 2 menit.

---

## 3. Prosedur Backup & Pemulihan Data
**Kejaran Rutin (Backup Schedule):**
- Eksekusi `pg_dump webgis_tembakau > backup_YYYYMMDD.sql` setiap 24 jam melalui cron job server.

**Rencana Pulih (Recovery Plan):**
1. Hentikan container aplikasi.
2. Restore basis data: `docker exec -i pamekasan-db psql -U postgres -d webgis_tembakau < backup_fresh.sql`.
3. Mulai ulang container aplikasi dan tunggu health check stabil.

---

## 4. Rencana Rollback (Apabila Cutover Gagal)
Jika ditemukan defect kritis atau error basis data setelah cutover ke sistem Green:

1. **Langkah 1 (Stop)**: Putuskan traffic publik dari server Green segera.
2. **Langkah 2 (Kembalikan DNS)**: Arahkan kembali traffic publik ke container Blue.
3. **Langkah 3 (Audit)**: Lakukan investigasi error log (`php artisan log:read`) dan database migration rollback jika perlu.
4. **Langkah 4 (Re-deploy)**: Perbaiki kode atau skrip migrasi, lalu ulangi prosedur cutover setelah masa validasi minimum 1 jam.

---

## 5. Catatan Keamanan & Rahasia
- **Jangan pernah** mengunggah file `.env` asli ke repository Git publik. Gunakan `.env.example`.
- Kunci aplikasi (`APP_KEY`) dan sandi PostgreSQL wajib disimpan secara terpisah sebagai environment variable di server produksi, bukan di hardcode.

# Backup & Restore — libfree

libfree menyimpan dua jenis data yang harus dicadangkan bersama:

1. **Database** — katalog, akun, ulasan, bookmark, submission, dan audit lisensi.
2. **Berkas privat** — naskah dan sampul di `storage/app/private` (disk `local`).

Konfigurasi rahasia (`.env`, `APP_KEY`) disimpan terpisah di secret manager; jangan menaruhnya di arsip backup yang sama dengan database bila arsip itu diunggah ke object storage.

## 1. Skrip backup

`scripts/backup.sh` membuat dump database + arsip storage, menerapkan retensi, dan opsional mengunggah ke object storage.

Variabel yang dibaca dari environment atau `.env`:

| Variabel | Default | Keterangan |
|---|---|---|
| `BACKUP_DIR` | `storage/backups` | Direktori tujuan backup. |
| `BACKUP_RETENTION_DAYS` | `14` | Hapus berkas `libfree-*` lebih tua dari N hari. |
| `BACKUP_STORAGE_PATHS` | `storage/app/private` | Path relatif yang diarsipkan (pisahkan spasi). |
| `BACKUP_S3_URI` | kosong | Opsional, mis. `s3://bucket/libfree`; butuh AWS CLI. |
| `ENV_FILE` | `.env` | Lokasi file environment. |

Jalankan manual:

```bash
cd /var/www/libfree
./scripts/backup.sh
```

Hasil (contoh):

```
storage/backups/libfree-db-20261116-021500.sql.gz
storage/backups/libfree-files-20261116-021500.tar.gz
```

Skrip memilih perintah sesuai `DB_CONNECTION`:

- **mysql/mariadb** → `mysqldump --single-transaction --quick --routines --triggers`
- **pgsql** → `pg_dump --no-owner --no-acl`
- **sqlite** → `sqlite3 .backup` (fallback salinan berkas)

## 2. Jadwal harian

```cron
15 2 * * * /var/www/libfree/scripts/backup.sh >> /var/log/libfree-backup.log 2>&1
```

Pastikan direktori `BACKUP_DIR` berada di volume yang tidak ikut terhapus saat redeploy. Untuk produksi, unggah juga ke lokasi offsite (S3/B2/rsync) dan uji restore secara berkala.

## 3. Retensi

Skrip menghapus otomatis `libfree-*` yang lebih tua dari `BACKUP_RETENTION_DAYS` (default 14 hari). Untuk kebijakan lebih panjang, salin hasil backup ke penyimpanan arsip sebelum retensi berjalan.

## 4. Restore database

### MySQL / MariaDB

```bash
gunzip -c /var/backups/libfree/libfree-db-20261116-021500.sql.gz | mysql -u libfree -p libfree
```

### PostgreSQL

```bash
gunzip -c /var/backups/libfree/libfree-db-20261116-021500.sql.gz | psql -U libfree -d libfree
```

### SQLite

```bash
systemctl stop libfree-queue   # hentikan penulis lain
gunzip -c /var/backups/libfree/libfree-db-20261116-021500.sqlite.gz > database/database.sqlite
chown www-data:www-data database/database.sqlite
```

## 5. Restore berkas privat

```bash
tar -xzf /var/backups/libfree/libfree-files-20261116-021500.tar.gz -C /var/www/libfree
chown -R www-data:www-data /var/www/libfree/storage/app/private
```

Setelah restore:

```bash
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
php artisan queue:restart
curl -fsS https://libfree.id/health
```

## 6. Verifikasi arsip

```bash
gzip -t /var/backups/libfree/libfree-db-*.gz
tar -tzf /var/backups/libfree/libfree-files-*.tar.gz | head
```

Lakukan **uji restore ke lingkungan staging minimal setiap kuartal**. Backup yang belum pernah diuji restore belum bisa dianggap andal.

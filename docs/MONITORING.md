# Monitoring & Operasional — libfree

## 1. Health check

Dua endpoint tersedia:

| Endpoint | Kegunaan | Respons |
|---|---|---|
| `GET /up` | Laravel boot check (sangat ringan). | `200` bila aplikasi bisa bootstrap. |
| `GET /health` | Readiness check: database, cache, storage privat, dan tabel queue (bila `QUEUE_CONNECTION=database`). | `200` `{"status":"ok"}` atau `503` `{"status":"degraded"}`. |

Contoh:

```bash
curl -fsS https://libfree.id/up
curl -fsS https://libfree.id/health
```

Pasang uptime monitor (UptimeRobot, Better Stack, Pingdom, atau healthcheck platform) untuk:

- `https://libfree.id/up` — interval 60 detik, alert ke email/Slack/Telegram.
- `https://libfree.id/health` — interval 5 menit, alert bila status `503`.
- `https://libfree.id/` — pengecekan halaman publik utama (opsional, mendeteksi error render).

`/health` hanya mengembalikan status agregat, bukan detail internal, sehingga aman dipanggil publik. Bila ingin membatasi akses, batasi di Nginx/WAF.

## 2. Log aplikasi

Konfigurasi produksi yang disarankan (lihat `.env.production.example`):

```dotenv
LOG_CHANNEL=daily
LOG_LEVEL=warning
LOG_DAILY_DAYS=14
```

Perintah operasional:

```bash
# Ikuti log hari ini
tail -f storage/logs/laravel-$(date +%F).log

# Cari error dan exception
grep -Ei "ERROR|CRITICAL|EMERGENCY" storage/logs/laravel-*.log | tail -50
```

Untuk produksi serius, kirim log ke agregator (Loki, Papertrail, Datadog, CloudWatch) dan buat alert untuk level `ERROR` ke atas. Jangan biarkan `APP_DEBUG=true` di produksi karena stack trace dan nilai konfigurasi bisa bocor.

## 3. Queue

Worker berjalan sebagai service systemd (lihat `docs/DEPLOYMENT.md`). Cek status dan kegagalan:

```bash
systemctl status libfree-queue
journalctl -u libfree-queue -n 100 --no-pager

php artisan queue:failed
php artisan queue:retry all
php artisan queue:flush          # setelah insiden selesai
```

Ukuran antrean (satu kali):

```bash
php artisan queue:monitor default --max=1000 --json
```

Jalankan pemeriksaan ini dari cron/monitor eksternal dan alert bila jumlah job melewati ambang. Setelah deploy, selalu jalankan `php artisan queue:restart`.

## 4. Scheduler

Cek daftar tugas terjadwal:

```bash
php artisan schedule:list
```

Pastikan cron berjalan:

```cron
* * * * * cd /var/www/libfree && php artisan schedule:run >> /dev/null 2>&1
```

Tugas yang saat ini dijadwalkan: pembersihan token reset kata sandi (15 menit), pembersihan failed job (harian), dan pembersihan batch (harian). Alternatif cron adalah `php artisan schedule:work` sebagai service systemd.

## 5. Backup

Backup harian dijalankan lewat cron (`scripts/backup.sh`). Pantau:

- keberhasilan skrip di `/var/log/libfree-backup.log`,
- ukuran arsip tidak turun drastis,
- `gzip -t` pada arsip terbaru,
- uji restore berkala (lihat `docs/BACKUP.md`).

## 6. Pemeriksaan deploy

Setelah setiap deploy:

```bash
php artisan about
php artisan schedule:list
php artisan queue:failed
curl -fsS https://libfree.id/health
curl -fsS https://libfree.id/robots.txt
curl -fsS https://libfree.id/sitemap.xml | head
```

Indikator sehat:

- `/health` → `200` dan seluruh nilai `checks` adalah `ok`.
- `php artisan about` → `environment: production`, `debug: false`, cache config/route/view aktif.
- `robots.txt` memuat `Sitemap: https://.../sitemap.xml`.
- `sitemap.xml` memuat 15 judul publik (sesuai katalog teraudit) dan tidak memuat URL admin/API.

## 7. Insiden umum

| Gejala | Cek pertama |
|---|---|
| Halaman 500 | `storage/logs/laravel-*.log`, izin `storage/` & `bootstrap/cache`. |
| Semua pengguna logout | `APP_KEY` berubah, session driver/Redis mati, cookie `Secure` diakses via HTTP. |
| Email reset tidak masuk | `MAIL_MAILER` masih `log`, kredensial SMTP, SPF/DKIM/DMARC, folder spam. |
| Unggahan gagal > 2 MB | `upload_max_filesize`/`post_max_size` PHP dan `client_max_body_size` Nginx. |
| Aset 404 setelah deploy | `npm run build` belum dijalankan atau `public/build` tidak tersalin. |
| Antrean menumpuk | worker systemd mati, Redis/database queue tidak terjangkau, job gagal berulang. |

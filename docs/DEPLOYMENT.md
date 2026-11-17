# Deployment Produksi — libfree

Dokumen ini adalah panduan menaikkan libfree (Laravel 13 + Vue 3 SPA) ke server produksi. Ringkasan: siapkan PHP 8.3+, database, Redis (disarankan), lalu build aset, migrasi, dan cache konfigurasi.

## 1. Prasyarat server

- **PHP 8.3+** dengan ekstensi: `pdo`, `pdo_mysql` (atau `pdo_pgsql`/`pdo_sqlite`), `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `bcmath`.
- **Composer 2** dan **Node.js 20+** (build aset di server atau di pipeline CI).
- **MySQL 8 / MariaDB 10.6+** (disarankan). PostgreSQL juga didukung; SQLite hanya untuk instalasi kecil.
- **Redis** (disarankan untuk session/cache/queue). Alternatif tanpa Redis: `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database` dan jalankan worker database.
- **Nginx** + PHP-FPM, sertifikat TLS (Let's Encrypt), dan domain yang mengarah ke server.
- **Mail relay** (SMTP/Postmark/SES/Resend) dan kotak surat nyata untuk `halo@libfree.id`.

## 2. Langkah deploy

```bash
cd /var/www/libfree

# 1. Dependensi PHP produksi
composer install --no-dev --optimize-autoloader --no-interaction

# 2. Konfigurasi environment
cp .env.production.example .env
php artisan key:generate

# 3. Build aset frontend (Vite)
npm ci --ignore-scripts        # gunakan `npm install --ignore-scripts` bila lockfile tidak tersedia
npm run build

# 4. Skema database + data katalog awal
php artisan migrate --force
php artisan db:seed --class=CatalogSeeder --force   # hanya saat instalasi baru

# 5. Cache produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Setelah itu verifikasi:

```bash
php artisan about
curl -fsS https://libfree.id/health
curl -fsS https://libfree.id/robots.txt
curl -fsS https://libfree.id/sitemap.xml | head
```

### Setiap deploy berikutnya

```bash
php artisan down --render="errors::503" --retry=60   # opsional
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --ignore-scripts && npm run build   # fallback: npm install --ignore-scripts
php artisan migrate --force
php artisan optimize            # config + route + view + event cache
php artisan queue:restart       # agar worker memakai kode baru
php artisan up
```

> `route:cache` aman karena seluruh route memakai controller (tidak ada closure sebagai action).

## 3. Izin direktori

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache
```

Naskah dan sampul disimpan di `storage/app/private` (disk `local`) dan **tidak** perlu `storage:link`. Endpoint publik/berotorisasi yang men-stream berkas ada di `routes/api.php`.

## 4. PHP-FPM

Contoh `php.ini` / pool override untuk unggahan naskah maksimal 50 MB:

```ini
upload_max_filesize = 52M
post_max_size = 56M
memory_limit = 256M
max_execution_time = 120
max_input_time = 120
```

Aktifkan OPcache di produksi. Batas 50 MB divalidasi juga di aplikasi (`SubmissionController`, `AdminContentController`).

## 5. Nginx

```nginx
server {
    listen 80;
    server_name libfree.id www.libfree.id;
    return 301 https://libfree.id$request_uri;
}

server {
    listen 443 ssl http2;
    server_name libfree.id;
    root /var/www/libfree/public;
    index index.php;

    ssl_certificate     /etc/letsencrypt/live/libfree.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/libfree.id/privkey.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;

    client_max_body_size 52m;

    # Header keamanan utama sudah dikirim middleware SecurityHeaders.
    add_header X-Content-Type-Options nosniff always;
    add_header X-Frame-Options DENY always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_read_timeout 120s;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Aset Vite ber-hash boleh di-cache lama.
    location /build/ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Worker pdf.js memakai ekstensi .mjs yang belum tentu dikenali mime.types
    # Nginx; tanpa MIME JavaScript, module worker ditolak browser.
    location ~* \.mjs$ {
        default_type application/javascript;
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }
}
```

`try_files` wajib mengarah ke `index.php` agar deep link SPA (`/katalog`, `/buku/...`) tetap dirender Laravel, bukan 404 Nginx.

## 6. Queue worker

Buat unit systemd `/etc/systemd/system/libfree-queue.service`:

```ini
[Unit]
Description=libfree queue worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
RestartSec=5
WorkingDirectory=/var/www/libfree
ExecStart=/usr/bin/php /var/www/libfree/artisan queue:work --sleep=3 --tries=3 --max-time=3600
KillSignal=SIGTERM
TimeoutStopSec=30

[Install]
WantedBy=multi-user.target
```

```bash
systemctl daemon-reload
systemctl enable --now libfree-queue
```

## 7. Scheduler

Tambahkan cron untuk scheduler Laravel (wajib, agar pembersihan token reset, failed job, dan batch berjalan):

```cron
* * * * * cd /var/www/libfree && php artisan schedule:run >> /dev/null 2>&1
```

Backup harian dijelaskan di `docs/BACKUP.md`.

## 8. Pemeriksaan keamanan produksi

- `APP_DEBUG=false` dan `APP_ENV=production`.
- `APP_KEY` terisi dan unik; jangan pernah commit `.env`.
- Cookie sesi: `SESSION_SECURE_COOKIE=true`, `SESSION_HTTP_ONLY=true`, `SESSION_ENCRYPT=true`.
- Set `TRUSTED_PROXIES` bila memakai reverse proxy/load balancer.
- Middleware `SecurityHeaders` mengirim CSP, HSTS (saat HTTPS), `X-Frame-Options`, dan `Permissions-Policy`.
- Batasi akses `/admin/*` di level aplikasi (role admin) dan, bila perlu, di WAF/firewall.
- Pastikan mailbox `halo@libfree.id` aktif, serta SPF/DKIM/DMARC domain sudah benar agar email reset kata sandi tidak masuk spam.
- Rotasi kredensial SMTP/database secara berkala; simpan di secret manager bila tersedia.

## 9. Mailer

Email reset kata sandi memakai broker bawaan Laravel dan tautan diarahkan ke route Vue `/atur-ulang-sandi` (lihat `AppServiceProvider`). Dengan `MAIL_MAILER=log`, email hanya ditulis ke log dan **tidak terkirim** — jangan gunakan nilai itu di produksi.

Uji setelah konfigurasi:

```bash
php artisan tinker --execute="Mail::raw('Tes libfree', fn (\$m) => \$m->to('ops@example.com')->subject('Tes'));"
```

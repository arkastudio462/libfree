# PRD — libfree: Perpustakaan Digital Terbuka

| | |
|---|---|
| **Dokumen** | Product Requirements Document (PRD) |
| **Versi** | 0.1 — Draft |
| **Tanggal** | 12 November 2026 |
| **Status** | Draft untuk review |
| **Project** | `libfree` (Laravel 13 + Vue 3 SPA) |

---

## 1. Ringkasan Eksekutif

**libfree** adalah perpustakaan digital terbuka berbahasa Indonesia: pembaca dapat mencari, membaca, dan mengunduh buku secara gratis tanpa biaya langganan. Target konten meliputi sastra nusantara, ensiklopedia sains, arsip sejarah, dan dongeng anak.

Kondisi pengembangan saat ini: **frontend 3 halaman (Beranda, Katalog, Detail Buku) sudah lengkap dan berfungsi**, di-port manual dari template HTML statis, sedangkan **backend masih berupa skeleton Laravel** — belum ada API, model buku, maupun data di database. Seluruh data buku masih hardcoded di dalam template Vue.

PRD ini mendokumentasikan kondisi saat ini (Bagian 3) dan memecah pekerjaan berikutnya menjadi milestone fitur (Bagian 5) agar pengembangan berikutnya terstruktur: perbaikan fondasi database → data & API buku → autentikasi → fitur personal → reader & unduhan.

---

## 2. Latar Belakang & Visi Produk

### 2.1 Pernyataan produk

> "Baca buku gratis, kapan saja, di mana saja." — Akses puluhan ribu buku sastra nusantara, ensiklopedia sains, arsip sejarah, dan dongeng anak dalam genggaman, tanpa batasan kuota dan tanpa biaya langganan.

### 2.2 Nilai inti (dari copy produk yang sudah ada)

- **100% terbuka & bebas akses** — tanpa biaya langganan.
- **Filosofi terbuka** — bagian "Mengapa Harus libfree?" di Beranda.
- **Kurasi manusia** — koleksi kurasi pekan ini (mis. "Menelusuri Jejak Perlawanan Sastra Pra-Kemerdekaan").

### 2.3 Visi jangka menengah

Menjadi rujukan perpustakaan digital Indonesia: katalog yang mudah dijelajah, pengalaman baca nyaman di web, dan fitur komunitas (ulasan, rak baca) yang menjaga pembaca kembali.

---

## 3. Analisa Kondisi Saat Ini

### 3.1 Stack teknologi

| Aspek | Teknologi |
|---|---|
| Backend | Laravel 13 (PHP `^8.3`, env PHP 8.4), Composer 2 |
| Frontend | Vue 3 SFC + Vue Router (history mode) + Vite |
| Styling | Tailwind CSS v4 dengan konfigurasi v3 (di-port dari CDN template) |
| Database | SQLite (`database/database.sqlite`), session/cache/queue berbasis database |
| Testing | PHPUnit 12 + Pint (tanpa Pest/PHPStan/Rector) |
| Bahasa konten | Bahasa Indonesia |

### 3.2 Apa yang sudah ada

| Komponen | Status | Catatan |
|---|---|---|
| Shell SPA + router | ✅ Selesai | `routes/web.php` me-render `app.blade.php` untuk `/`, `/katalog`, `/buku/{slug}` + fallback 404 |
| Halaman Beranda | ✅ Selesai (UI) | Hero + pencarian, buku terpopuler, penulis terfavorit, 8 kategori, koleksi khusus, "Mengapa Harus libfree?" — `resources/js/pages/Home.vue` (878 baris) |
| Halaman Katalog | ✅ Selesai (UI) | Filter sidebar (kategori + jumlah, format, sortir), grid 13 buku, pagination, banner kurasi — `resources/js/pages/Katalog.vue` (905 baris) |
| Halaman Detail Buku | ✅ Selesai (UI) | Info buku + rating, tab ringkasan/isi/penulis, bookmark, copy kutipan, share, reader modal, unduh EPUB/PDF, urutan serial, buku terkait, ulasan & diskusi, form ulasan — `resources/js/pages/BookDetail.vue` (766 baris) |
| Komponen bersama | ✅ Selesai | `MainNavbar`, `MainFooter`, `AmbientBackground` |
| Migrasi dasar | ✅ Selesai | `users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` |
| Referensi desain | ✅ Ada | `public/templates/*/code.html` (3 halaman sumber) |

### 3.3 Apa yang belum ada

| Komponen | Status | Dampak |
|---|---|---|
| Model & data buku | ❌ Belum ada | Tabel `books`/`categories`/`authors` tidak ada; 13 buku di katalog & 1 buku detail hardcoded di template |
| API | ❌ Belum ada | Tidak ada endpoint JSON; frontend tidak bisa jadi data-driven |
| Autentikasi | ❌ Belum ada | Tombol "Masuk"/"Daftar Gratis" di navbar hanya anchor `#masuk`/`#daftar` |
| Fitur personal | ❌ Simulasi | Bookmark/rak, ulasan, share dikerjakan client-side (state hilang saat reload, tidak lintas perangkat) |
| Seeder data buku | ❌ Belum ada | `DatabaseSeeder` hanya membuat 1 user test |
| CI / static analysis | ❌ Belum ada | Gate manual: `pint --test` → `npm run build` → `composer test` |
| Test | ❌ Minimal | Hanya 2 test example bawaan skeleton |

### 3.4 Temuan bug terkait database

#### Bug DB-1: Aplikasi dijalankan sebelum database siap (file sqlite tidak ada)

- **Gejala:** `Illuminate\Database\SQLiteDatabaseDoesNotExistException: Database file at path [/root/libfree/database/database.sqlite] does not exist` — tercatat **42 kali** di `storage/logs/laravel.log`, disusul `no such table: sessions` dan `no such table: cache`. Request yang membutuhkan session/cache gagal (server error), dan worker antrian (`queue:listen`) error berkala.
- **Akar masalah (dikoreksi setelah verifikasi source framework):**
  1. File `database/database.sqlite` di-*gitignore* (`database/.gitignore` → `*.sqlite*`) sehingga tidak ikut ter-clone, dan **proses dev (`php artisan dev` → serve + queue:listen) dijalankan sebelum database pernah di-migrate** — bukti: log juga mencatat `key:generate` gagal karena `.env` belum ada (11:26), baru berikutnya error DB berulang (11:28–11:31) sampai file dibuat + dimigrasi.
  2. Hanya perintah `migrate --force` yang **auto-create** file sqlite yang hilang (`MigrateCommand::handleMissingDatabase()` → `createMissingSqliteDatabase()`). **Semua jalur lain** — `migrate:status`, maupun driver session/cache/queue database saat app berjaka — melempar `SQLiteDatabaseDoesNotExistException` (`SQLiteConnector`), sehingga app/worker error selama file belum ada dan ter-migrasi.
- **Dampak:** clone baru yang langsung `composer dev` tanpa menyelesaikan setup → error berulang di log (session, cache, queue) dan halaman 500; `php artisan migrate:status` juga gagal.
- **Rencana perbaikan (M0):**
  1. Tambahkan pembuatan file sqlite eksplisit ke script `composer setup` sebelum `migrate --force`: `@php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"` — konsisten dengan `post-create-project-cmd` bawaan Laravel, sekaligus hardening agar setup tidak bergantung pada perilaku auto-create `migrate --force` dan `migrate:status` langsung bisa jalan.
  2. Aturan urutan: **`composer setup` wajib sebelum `composer dev`** (sudah terdokumentasi di `AGENTS.md` → *First-time setup*).
  3. Verifikasi pada clone bersih: setup penuh menghasilkan DB ter-migrasi tanpa error, dan request + worker tidak menulis error DB baru ke log.

#### Bug DB-2 (sekunder): urutan langkah setup rentan

- Log menunjukkan `key:generate` pernah gagal dengan `file_get_contents(/root/libfree/.env): Failed to open stream` — artinya perintah dijalankan tanpa `.env` tersedia. Skrip `setup` sudah menyalin `.env.example` sebelum `key:generate`, sehingga ini hanya terjadi bila langkah dilewati. Tetap dicatat sebagai risiko dokumentasi (lihat Bagian 9).

---

## 4. Target Pengguna

| Persona | Kebutuhan utama | Fitur yang relevan |
|---|---|---|
| **Pembaca umum** | Membaca buku gratis tanpa hambatan, lanjutkan baca terakhir | Katalog, pencarian, rak baca (bookmark), reader |
| **Pelajar / mahasiswa** | Cari bacaan per topik/kategori, unduh untuk dibaca offline | Filter kategori & format, unduh EPUB/PDF |
| **Peneliti sastra & sejarah** | Telusuri naskah kuno, arsip, ulasan kritis | Filter naskah/arsip, detail buku lengkap, kutipan + share, ulasan komunitas |

---

## 5. Kebutuhan Fitur (Roadmap)

Prioritas memakai MoSCoW: **M** = Must, **S** = Should, **C** = Could, **W** = Won't (untuk rilis ini).

### M0 — Fondasi & perbaikan database *(Must — bug fix)*

> **Status: SELESAI (12 Nov 2026)** — `touch` sqlite ditambahkan ke `composer setup`; terbukti pada simulasi clone bersih (`.env` baru + DB kosong): setup menghasilkan DB ter-migrasi + build sukses tanpa error; `migrate:status` semua Ran; request 3 rute + worker antrian tidak menulis error DB baru ke log; gate `pint` → `build` → `test` lulus. Catatan: akar bug diluruskan di Bagian 3.4 (error log berasal dari menjalankan app sebelum DB siap, bukan kegagalan langkah migrate).

| ID | Kebutuhan | Kriteria penerimaan |
|---|---|---|
| M0.1 | `composer setup` berhasil di clone baru tanpa file sqlite | Menjalankan `composer setup` dari clone bersih menghasilkan DB ter-migrasi tanpa error; file sqlite dibuat otomatis jika belum ada |
| M0.2 | Tidak ada error DB di log saat app berjalan | Request ke `/`, `/katalog`, `/buku/{slug}` + worker antrian tidak menulis `SQLiteDatabaseDoesNotExistException` / `no such table` ke `storage/logs/laravel.log` |
| M0.3 | Verifikasi status migrasi mudah | `php artisan migrate:status` menunjukkan semua migrasi `Ran` pada setup standar |

### M1 — Data buku & API *(Must)*

> **Status: SELESAI (12 Nov 2026)** — Backend katalog lengkap (migrasi catalog, model `Book`/`Category`/`Author`/`Review`, `BookController` index+show dengan fallback detail, `CategoryController`, `AuthorController`, `routes/api.php`, `CatalogSeeder` 15 buku/16 penulis/6 kategori/3 review idempoten); pencarian diperluas jadi token OR + nama kategori agar quick-tag menghasilkan "hasil yang cocok"; frontend ketiga halaman (`Katalog.vue`, `Home.vue`, `BookDetail.vue`) sudah data-driven dengan state reaktif (filter sync URL, carousel hero, tab/format/bookmark/copy/share/review); `tests/Feature/Api/CatalogApiTest.php` 15 test; gate `pint` → `build` → `composer test` (15/15) lulus; rute `/`, `/katalog`, `/buku/{slug}` merender shell 200.

| ID | Kebutuhan | Kriteria penerimaan |
|---|---|---|
| M1.1 | Model data buku | Tabel `books`, `categories`, `authors` (+ relasi) dimigrasikan; model Eloquent sesuai |
| M1.2 | Seeder data awal | Minimal 13 buku yang sudah tampil di katalog + kategori (Sastra Klasik, Sejarah & Biografi, Sains & Matematika, Filsafat & Cerita Rakyat, Naskah Kuno, dll.) ter-seed; `db:seed` idempoten |
| M1.3 | API katalog | `GET /api/buku` mendukung filter kategori, format, pencarian (`q`), sortir, dan pagination — mengembalikan JSON |
| M1.4 | API detail buku | `GET /api/buku/{slug}` mengembalikan detail + penulis + kategori + buku terkait; 404 JSON untuk slug tak dikenal |
| M1.5 | Katalog jadi data-driven | `Katalog.vue` membaca data dari API (bukan hardcoded), state filter jadi reaktif, filter benar-benar memengaruhi hasil |
| M1.6 | Beranda jadi data-driven | Slot buku terpopuler & penulis terfavorit diambil dari API/DB |
| M1.7 | Detail buku dinamis | Route `/buku/{slug}` me-render data dari API sesuai slug (bukan hanya "Bumi Manusia") |
| M1.8 | Pencarian berfungsi | Input pencarian di hero & quick-tag meneruskan `q` ke katalog dan menampilkan hasil yang cocok |

### M2 — Autentikasi *(Should)*

| ID | Kebutuhan | Kriteria penerimaan |
|---|---|---|
| M2.1 | Daftar & masuk | Tombol "Masuk" / "Daftar Gratis" di navbar membuka form yang berfungsi (registrasi + login session-based) |
| M2.2 | Proteksi dasar | Route/endpoint personal membutuhkan sesi login; password di-hash (bcrypt) |
| M2.3 | Status login | Navbar menampilkan state pengguna (nama/avatar + keluar) setelah masuk |

### M3 — Fitur personal *(Should)*

| ID | Kebutuhan | Kriteria penerimaan |
|---|---|---|
| M3.1 | Rak baca (bookmark) | Bookmark tersimpan per pengguna di DB, bertahan setelah reload & lintas perangkat; indikator aktif pada tombol "Simpan ke Rak" |
| M3.2 | Ulasan & rating | Ulasan tersimpan di DB, tampil pada halaman detail, tersimpan untuk pengguna yang login |
| M3.3 | Form ulasan fungsional | Form "Kirimkan Ulasanmu" menyimpan ke DB (bukan hanya toggle UI) |
| M3.4 | Share | Tombol share memakai Web Share API / salin tautan dengan URL benar per buku |

### M4 — Reader & unduhan *(Could)*

| ID | Kebutuhan | Kriteria penerimaan |
|---|---|---|
| M4.1 | Reader modal | Reader membuka isi buku dari data (bukan placeholder), dapat ditutup, navigasi nyaman |
| M4.2 | Unduh EPUB/PDF | Tombol unduh mengunduh berkas valid atau, bila belum ada berkas, memberi pesan yang jelas (bukan link mati `#`) |

### Won't (rilis ini)

- Aplikasi mobile native, notifikasi push, integrasi pihak ketiga (perpustakaan daerah, dsb.).
- Sistem pinjam fisik / manajemen koleksi non-digital.
- Multi-bahasa UI (konten tetap Bahasa Indonesia).

---

## 6. Persyaratan Non-Fungsional

| Area | Persyaratan |
|---|---|
| **Performa** | Halaman utama tetap responsif; daftar katalog di-batch/paginasi (tidak memuat seluruh katalog sekaligus); aset gambar ter-optimalkan |
| **Deep link & SEO** | URL `/`, `/katalog`, `/buku/{slug}` dapat diakses langsung (server me-render shell SPA — sudah ada, pertahankan); status 404 untuk URL tak dikenal |
| **Aksesibilitas** | Atribut `aria-label` pada navigasi & tombol ikon (sudah sebagian ada, dipertahankan); kontras warna brand tetap terbaca |
| **Keamanan** | Password di-hash; input divalidasi di server; escape konten ulasan pengguna; tidak ada kredensial di repo |
| **Keandalan DB** | Setup dari clone bersih selalu berhasil (lihat M0); tidak ada error DB berulang di log |
| **Browser** | Modern evergreen (Chrome, Firefox, Safari, Edge) — setara dukungan Tailwind v4/Vite |
| **Konsistensi UI** | Perubahan fitur harus mempertahankan gaya neo-card/pill dari template; jangan "merapikan" tanpa membandingkan `public/templates/*` |

---

## 7. Spesifikasi Teknis Ringkas

### 7.1 Skema database (yang akan ditambahkan)

- `categories` — id, nama, slug, ikon/label (untuk filter kategori).
- `authors` — id, nama, slug, bio singkat, foto.
- `books` — id, judul, slug (unik), penulis_id, kategori_id, sinopsis, tahun, ISBN, rating, jumlah_ulasan, format tersedia (JSON/flag), file EPUB/PDF, gambar sampul, flag kurasi/terpopuler.
- `reviews` — id, book_id, user_id, rating, isi, timestamp.
- `bookmarks` — id, user_id, book_id, timestamp (unique per pasangan).

> Migrasi harus memakai konvensi project: nama tabel snake_case, `timestamps()`, dan tetap kompatibel SQLite.

### 7.2 Kontrak API (usulan)

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/api/buku?q=&kategori=&format=&sort=&page=` | Daftar buku + pagination |
| GET | `/api/buku/{slug}` | Detail satu buku |
| GET | `/api/kategori` | Daftar kategori + jumlah buku |
| GET | `/api/penulis` | Daftar penulis |
| POST | `/api/daftar`, `/api/masuk`, `/api/keluar` | Autentikasi (M2) |
| GET/POST | `/api/buku/{slug}/ulasan` | Baca/tulis ulasan (M3) |
| POST/DELETE | `/api/buku/{slug}/bookmark` | Kelola rak baca (M3) |

### 7.3 Catatan refactor frontend

- Interaksi sekarang adalah port handler DOM vanilla di `<script setup>` tiap halaman (tab, bookmark, copy kutipan, share, filter). Saat halaman menjadi data-driven, refactor ke **state reaktif** (`ref`/`computed`) sesuai catatan `AGENTS.md`.
- `vite.config.js` `template.whitespace: 'preserve'` dan urutan layer CSS wajib dipertahankan agar fidelitas visual tetap sama dengan template.

---

## 8. Milestone & Urutan Pekerjaan

```
M0 Fondasi DB (bug fix)  →  M1 Data & API buku  →  M2 Autentikasi
                                        └──────────→  M3 Fitur personal (bookmark, ulasan)
                                        └──────────→  M4 Reader & unduh
```

- **M0** mendahului semua (fondasi setup harus hijau).
- **M1** adalah pengungkit terbesar: setelah ini UI berhenti hardcoded.
- **M2** diperlukan sebelum **M3** (bookmark & ulasan perlu pengguna).
- **M4** bisa paralel dengan M3 bila sumber daya tersedia.

**Definisi selesai tiap milestone:** `./vendor/bin/pint --test` lulus → `npm run build` sukses → `composer test` lulus → verifikasi manual alur fitur pada halaman terkait.

---

## 9. Risiko & Ketergantungan

| Risiko | Dampak | Mitigasi |
|---|---|---|
| App/worker dijalankan sebelum DB siap (Bug DB-1) | Rintang pertama tiap lingkungan baru: error session/cache/queue berulang di log | `touch` eksplisit di script `setup` (M0.1) + wajib `composer setup` sebelum `composer dev` |
| `php artisan dev` (multiplex) berjalan saat setup | Proses worker menulis error DB ke log dan membingungkan | Jalankan setup di keadaan bersih; cek `migrate:status` setelahnya |
| Data buku hanya ada di template statis | Kehilangan data bila template di-port ulang | Pindahkan data ke seeder sebagai satu sumber kebenaran |
| Refactor handler DOM ke state reaktif besar-besaran | Regresi visual/interaksi | Refactor per halaman, bandingkan dengan `public/templates/*` sebelum & sesudah |
| Tidak ada CI | Regresi lolos tanpa terdeteksi | Pertahankan gate manual 3 langkah; pertimbangkan CI di kemudian hari (di luar scope rilis ini) |

---

## 10. Lampiran

### A. Quality gate (wajib setelah perubahan)

```bash
./vendor/bin/pint --test   # gaya kode PHP
npm run build              # kompilasi Vue/Tailwind
composer test              # phpunit (config:clear dulu — sudah termuat di script)
```

### B. Perintah berguna

```bash
composer setup             # setup awal (lihat M0 untuk perbaikan)
php artisan migrate:status # cek status migrasi
php artisan db:seed        # seeding data buku (setelah M1)
php artisan serve          # server dev satu-shot untuk verifikasi
```

### C. Referensi

- Template desain sumber: `public/templates/{libfree_perpustakaan_digital_terbuka, koleksi_katalog_buku_libfree, detail_buku_bumi_manusia_libfree}/code.html`
- Aturan frontend & fidelity: `AGENTS.md`
- Struktur halaman: `resources/js/pages/{Home,Katalog,BookDetail}.vue`, `resources/js/router.js`

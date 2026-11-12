# AGENTS.md

**libfree** — Laravel 13 + Vue 3 SPA (perpustakaan digital, konten berbahasa Indonesia). PHP `^8.3` (env: PHP 8.4, Composer 2, Node 24). Backend masih skeleton Laravel (app/ hanya `User`, base `Controller`, `AppServiceProvider`); semua UI ada di Vue SFC di `resources/js/`. Tidak ada CI, pre-commit, atau static analysis. Bukan git repo.

## Commands

- **First-time setup**: `composer setup` (composer install → `.env` + key → `migrate --force` → `npm install --ignore-scripts` → `npm run build`).
- **Verify changes** (in order): `./vendor/bin/pint --test` → `npm run build` → `composer test`. That is the entire gate — only `phpunit` and `pint` are installed (no pest/phpstan/rector). Fix style with `./vendor/bin/pint` (no `pint.json` → Laravel default preset). `npm run build` wajib dijalankan setelah mengubah `resources/js` / `resources/css` — error kompilasi Vue/Tailwind muncul di sini.
- **Tests**: prefer `composer test` (it runs `config:clear` first — see gotcha below), or `php artisan test`.
  - Single file: `php artisan test tests/Feature/ExampleTest.php`
  - Single test: `php artisan test --filter=test_the_application_returns_a_successful_response`
- **Dev**: `composer dev` → `php artisan dev`, long-running multiplex of `serve` + `queue:listen` + `pail` + vite (Windows uses `concurrently`). For agent verification use one-shot `npm run build` + `php artisan serve` + curl, never the multiplex.

## Agent-optimized tool output (not a bug)

In agent shells (`AI_AGENT`, `OPENCODE*`, etc.), `php artisan test` / `vendor/bin/phpunit` and `./vendor/bin/pint` print **one machine-readable JSON line** instead of pretty console output. This is `laravel/pao` + Pint's built-in agent detection; exit codes stay correct (0 pass, 1 fail), and failures include actionable detail (pint: file paths + fixer names; phpunit: test, file, line, message) — parse it directly.

- Normal output for phpunit etc.: prefix `PAO_DISABLE=1` (e.g. `PAO_DISABLE=1 php artisan test`).
- pint **ignores** `PAO_DISABLE`; only unsetting the agent env vars (`AI_AGENT`, `OPENCODE*`) restores its colored output.

## Testing quirks

- `phpunit.xml` runs every test against **in-memory SQLite** with array cache/session and sync queue. Schema is *not* migrated automatically — DB-touching tests need `RefreshDatabase`.
- A cached config (`php artisan config:cache`) silently makes `phpunit.xml` `<env>` values lose to `.env`, so tests hit the real SQLite file. That is why `composer test` clears the config cache first; run `php artisan config:clear` yourself if you cached config.

## Frontend (Vue + Tailwind) — fidelity rules, jangan "dirapikan"

- Struktur: `resources/js/app.js` (entry) → `App.vue` (layout: ambient bg + navbar + `<RouterView/>` + footer + mobile nav) → `router.js` → `pages/{Home,Katalog,BookDetail}.vue`. `routes/web.php` hanya merender shell SPA (`/`, `/katalog`, `/buku/{slug}` + fallback 404); navigasi sepenuhnya ditangani Vue Router (history mode — server harus me-render `resources/views/app.blade.php` untuk semua URL tersebut).
- `public/templates/*/code.html` adalah **sumber referensi statis** (3 halaman asli: home, katalog, detail buku). Halaman Vue diekstrak dari file itu; kalau template di-update, port manual perubahannya ke `.vue` terkait (elemen bersama: `components/MainNavbar|MainFooter|AmbientBackground|MobileFloatingNav.vue`).
- `vite.config.js` menyetel `template.whitespace: 'preserve'` agar Vue mempertahankan whitespace persis seperti HTML asli — jangan dihapus.
- `resources/css/app.css` mem-port konfigurasi Tailwind CDN v3 template. Urutan layer eksplisit `fonts < theme < base < components < utilities`:
  - Google Fonts (Plus Jakarta Sans, Space Grotesk, Material Symbols Outlined) di-`@import` dari CSS dengan `layer(fonts)`; blade hanya menyisakan `preconnect`. Ini penting: CSS Google menetapkan `.material-symbols-outlined { font-size: 24px }`, jadi harus kalah dari utility `text-sm`/`text-base` seperti di template asli. Jangan pindahkan `<link>` font kembali ke blade.
  - Blok `<style>` asli template ada di `@layer components`, sehingga utility tetap menang saat konflik. Jangan dibiarkan tanpa layer (`@layer`) — CSS tanpa layer selalu mengalahkan utility berlayer.
  - `@theme` juga menyamakan detail v3: line-height `text-*` absolut (v4 unitless ikut diwariskan dan mengubah tinggi baris), `shadow-sm`/`shadow-xs`, palet `gray-*`/`neutral-*`, warna & shadow `brand-*`.
  - `@layer utilities` berisi shim `.border-1\.5` (config template), gradient `to right` (v4 default `in oklab`), dan `[type=checkbox]:checked { border-color: transparent }` (specificity forms-plugin v3).
  - `@layer base` menambah `button { cursor: pointer }` (preflight v4 memakai default) dan `option { padding: revert }`.
  - `@plugin '@tailwindcss/forms'` = padanan CDN `plugins=forms`; border-radius kontrol form direset ke 0 di `@layer components` agar input/select ber-`neo-pill` tetap kotak seperti template asli.
- Beberapa class di halaman sengaja ditambah agar cascade v3 tereplikasi: `sm:leading-*`/`lg:leading-*` pada heading/paragraf ber-`sm:text-*` (di v3 utility font-size responsif menimpa `leading-*` dasar), dan `mt-8` pada pagination katalog dihapus (di v3 `space-y-6` menang atas `mt-8`). Jangan "rapikan" tanpa membandingkan ulang dengan `public/templates`.
- Container desktop sengaja dilebarkan dari template: `max-w-8xl` (token `--container-8xl: 90rem` = 1440px di `app.css`), dipakai di `<main>` ketiga halaman + navbar + footer. Ubah token itu untuk menyetel lebar; jangan dikembalikan ke `max-w-7xl` tanpa permintaan.
- Interaksi (tab detail, bookmark, copy kutipan, share, filter katalog, quick-tag search) adalah port handler DOM vanilla di `<script setup>` tiap page — refactor ke state reaktif kalau halaman sudah dijadikan data-driven.
- Dev `.env` memakai session/cache/queue berbasis database → `php artisan migrate` dulu sebelum menguji alur itu. Frontend: `npm install` dulu (tidak ada lockfile), `npm run dev` untuk HMR (butuh `php artisan serve` juga).

## Instruction files

- `CLAUDE.md` is a stale copy of the old Laravel Boost bootstrap — ignore it.
- Laravel Boost is **not** installed. Only install it if the user asks (`composer require laravel/boost --dev` + `php artisan boost:install`): it adds agent tooling but **regenerates/overwrites this file and CLAUDE.md**.

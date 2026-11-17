<?php

use App\Http\Controllers\HealthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPA routes (Vue Router menangani seluruh navigasi di sisi client)
|--------------------------------------------------------------------------
|
| Every page is rendered through PageController so the shell receives
| per-route SEO metadata. The routes still answer with the same SPA shell;
| Vue Router takes over once the JavaScript bundle is loaded.
|
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/katalog', [PageController::class, 'catalog'])->name('katalog');
Route::get('/buku/{slug}', [PageController::class, 'book'])->name('buku.show');
Route::get('/baca/{slug}', [PageController::class, 'reader'])->name('baca.show');

Route::get('/masuk', [PageController::class, 'login'])->name('masuk');
Route::get('/daftar', [PageController::class, 'register'])->name('daftar');
Route::get('/lupa-sandi', [PageController::class, 'forgotPassword'])->name('lupa-sandi');
Route::get('/atur-ulang-sandi', [PageController::class, 'resetPassword'])->name('atur-ulang-sandi');

Route::get('/rak', [PageController::class, 'shelf'])->name('rak');
Route::get('/unggah', [PageController::class, 'upload'])->name('unggah');
Route::get('/admin/unggahan', [PageController::class, 'adminSubmissions'])->name('admin.unggahan');
Route::get('/admin/konten', [PageController::class, 'adminContent'])->name('admin.konten');

Route::get('/kebijakan-privasi', [PageController::class, 'legal'])
    ->defaults('doc', 'privacy')
    ->name('legal.privacy');
Route::get('/syarat-ketentuan', [PageController::class, 'legal'])
    ->defaults('doc', 'terms')
    ->name('legal.terms');
Route::get('/lisensi', [PageController::class, 'legal'])
    ->defaults('doc', 'license')
    ->name('legal.license');

// Crawler and monitoring endpoints.
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/health', [HealthController::class, 'index'])->name('health');

// URL tak dikenal / deep link tetap merender shell SPA (status 404, noindex).
Route::fallback([PageController::class, 'fallback']);

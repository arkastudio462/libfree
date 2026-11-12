<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPA routes (Vue Router menangani seluruh navigasi di sisi client)
|--------------------------------------------------------------------------
*/

Route::view('/', 'app')->name('home');
Route::view('/katalog', 'app')->name('katalog');
Route::view('/buku/{slug}', 'app')->name('buku.show');

// URL tak dikenal / deep link tetap merender shell SPA (status 404).
Route::fallback(fn () => response()->view('app', [], 404));

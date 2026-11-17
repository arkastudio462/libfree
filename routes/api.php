<?php

use App\Http\Controllers\Api\AdminContentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DownloadController;
use App\Http\Controllers\Api\ReaderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPA API
|--------------------------------------------------------------------------
|
| The Vue app and the API share the same origin, so the whole API group runs
| with the `web` middleware: session cookies keep the reader logged in and
| non-GET requests are protected by the CSRF token in the app shell.
|
*/

Route::middleware('web')->group(function () {
    // Authentication
    Route::get('/csrf-token', [AuthController::class, 'csrfToken']);
    Route::get('/saya', [AuthController::class, 'me']);
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::post('/masuk', [AuthController::class, 'login']);
    Route::post('/lupa-sandi', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');
    Route::post('/atur-ulang-sandi', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');
    Route::post('/keluar', [AuthController::class, 'logout']);

    // Public catalog
    Route::get('/buku', [BookController::class, 'index']);
    Route::get('/buku/{slug}', [BookController::class, 'show']);
    Route::get('/buku/{slug}/baca', [ReaderController::class, 'show']);
    Route::get('/buku/{slug}/berkas', [DownloadController::class, 'read']);
    Route::get('/buku/{slug}/unduh', [DownloadController::class, 'manuscript']);
    Route::get('/buku/{slug}/sampul', [DownloadController::class, 'cover']);
    Route::get('/kategori', [CategoryController::class, 'index']);
    Route::get('/penulis', [AuthorController::class, 'index']);

    // Personal shelf (requires a session)
    Route::middleware('auth')->group(function () {
        Route::get('/rak', [BookmarkController::class, 'index']);
        Route::get('/buku/{slug}/bookmark', [BookmarkController::class, 'status']);
        Route::post('/buku/{slug}/bookmark', [BookmarkController::class, 'store']);
        Route::delete('/buku/{slug}/bookmark', [BookmarkController::class, 'destroy']);

        // Reader reviews
        Route::post('/buku/{slug}/ulasan', [ReviewController::class, 'store'])->middleware('throttle:20,1');
        Route::post('/ulasan/{review}/bermanfaat', [ReviewController::class, 'toggleHelpful'])->middleware('throttle:30,1');

        // Manuscript submissions
        Route::get('/unggahan', [SubmissionController::class, 'index']);
        Route::post('/unggahan', [SubmissionController::class, 'store'])->middleware('throttle:10,1');
        Route::get('/unggahan/{submission}/berkas/{type}', [SubmissionController::class, 'file']);
    });

    // Moderation (admin only)
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/unggahan', [SubmissionController::class, 'adminIndex']);
        Route::patch('/unggahan/{submission}', [SubmissionController::class, 'review']);
        Route::get('/konten', [AdminContentController::class, 'index']);
        Route::patch('/konten/{book}', [AdminContentController::class, 'update']);
        Route::post('/konten/{book}/berkas', [AdminContentController::class, 'upload']);
        Route::get('/konten/{book}/sampul', [AdminContentController::class, 'cover']);
    });
});

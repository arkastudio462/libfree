<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Support\Seo;

class PageController extends Controller
{
    /**
     * Render the SPA shell for the home page.
     */
    public function home()
    {
        return $this->render(Seo::home());
    }

    /**
     * Render the SPA shell for the public catalog.
     */
    public function catalog()
    {
        return $this->render(Seo::catalog());
    }

    /**
     * Render the SPA shell for a book detail URL.
     *
     * Unknown slugs still render the shell so the client can show its error
     * state, but the HTTP status is a real 404 for crawlers and deep links.
     */
    public function book(string $slug)
    {
        $book = $this->publishedBook($slug);

        return $book
            ? $this->render(Seo::book($book))
            : $this->render(Seo::notFound(), 404);
    }

    /**
     * Render the SPA shell for the in-browser reader.
     */
    public function reader(string $slug)
    {
        $book = $this->publishedBook($slug);

        return $book
            ? $this->render(Seo::reader($book))
            : $this->render(Seo::notFound(), 404);
    }

    public function login()
    {
        return $this->render(Seo::noindex(
            'Masuk — libfree',
            'Masuk ke akun libfree untuk menyimpan buku ke rak baca dan menulis ulasan.',
        ));
    }

    public function register()
    {
        return $this->render(Seo::noindex(
            'Daftar Akun — libfree',
            'Buat akun pembaca libfree gratis untuk menyimpan buku dan berbagi ulasan.',
        ));
    }

    public function forgotPassword()
    {
        return $this->render(Seo::noindex(
            'Lupa Kata Sandi — libfree',
            'Minta tautan atur ulang kata sandi untuk akun libfree-mu.',
        ));
    }

    public function resetPassword()
    {
        return $this->render(Seo::noindex(
            'Atur Ulang Kata Sandi — libfree',
            'Buat kata sandi baru untuk akun libfree-mu.',
        ));
    }

    public function shelf()
    {
        return $this->render(Seo::noindex(
            'Rak Baca — libfree',
            'Rak baca pribadi berisi judul yang kamu simpan di libfree.',
        ));
    }

    public function upload()
    {
        return $this->render(Seo::noindex(
            'Unggah Naskah — libfree',
            'Kirim naskah domain publik atau berlisensi terbuka untuk ditinjau kurator libfree.',
        ));
    }

    public function adminSubmissions()
    {
        return $this->render(Seo::noindex(
            'Tinjau Unggahan — libfree',
            'Panel kurator untuk meninjau naskah yang dikirim pembaca.',
        ));
    }

    public function adminContent()
    {
        return $this->render(Seo::noindex(
            'Audit Konten — libfree',
            'Panel kurator untuk memeriksa status lisensi dan publikasi koleksi.',
        ));
    }

    public function legal(string $doc)
    {
        return $this->render(Seo::legal($doc));
    }

    /**
     * Unknown URL: keep the SPA shell but return a real 404 and noindex.
     */
    public function fallback()
    {
        return $this->render(Seo::notFound(), 404);
    }

    private function publishedBook(string $slug): ?Book
    {
        return Book::query()
            ->published()
            ->with(['author:id,name,slug', 'category:id,name,slug'])
            ->where('slug', $slug)
            ->first();
    }

    private function render(array $seo, int $status = 200)
    {
        return response()->view('app', ['seo' => $seo], $status);
    }
}

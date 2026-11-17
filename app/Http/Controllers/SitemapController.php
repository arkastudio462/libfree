<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * XML sitemap for the public pages and every published book.
     */
    public function sitemap(): Response
    {
        $static = [
            ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => route('katalog'), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('legal.license'), 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => route('legal.privacy'), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => route('legal.terms'), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        $books = Book::query()
            ->published()
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at']);

        $xml = view('sitemap', [
            'static' => $static,
            'books' => $books,
        ])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * robots.txt is generated so the sitemap URL follows APP_URL.
     */
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /api/',
            'Disallow: /admin/',
            'Disallow: /rak',
            'Disallow: /unggah',
            'Disallow: /masuk',
            'Disallow: /daftar',
            'Disallow: /lupa-sandi',
            'Disallow: /atur-ulang-sandi',
            'Disallow: /baca/',
            '',
            'Sitemap: '.url('/sitemap.xml'),
            '',
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}

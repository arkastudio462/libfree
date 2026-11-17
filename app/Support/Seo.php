<?php

namespace App\Support;

use App\Models\Book;
use Illuminate\Support\Str;

/**
 * Builds the per-route metadata rendered into the SPA shell.
 *
 * The Vue app updates the same tags after client-side navigation (see
 * resources/js/lib/seo.js), so crawlers and social previews get a complete
 * server-rendered document while readers still get instant SPA routing.
 */
class Seo
{
    public const SITE_NAME = 'libfree';

    public const DEFAULT_TITLE = 'libfree — Perpustakaan Digital Terbuka & Gratis';

    public const DEFAULT_DESCRIPTION = 'Baca dan unduh buku klasik Indonesia yang bebas hak cipta secara gratis. Koleksi domain publik dan berlisensi terbuka, telah melewati audit lisensi.';

    public static function home(): array
    {
        return self::build([
            'title' => 'libfree — Baca Buku Klasik Gratis & Legal',
            'description' => 'Jelajahi koleksi buku domain publik dan berlisensi terbuka: sastra klasik, sejarah, sains, dan cerita rakyat nusantara. Gratis, tanpa iklan, dan telah melewati audit lisensi.',
            'canonical' => route('home'),
            'json_ld' => [
                self::organizationSchema(),
                self::websiteSchema(),
            ],
        ]);
    }

    public static function catalog(): array
    {
        return self::build([
            'title' => 'Katalog Buku — libfree',
            'description' => 'Cari buku berdasarkan judul, penulis, kategori, format, dan nilai kurasi. Semua judul yang tayang sudah melewati pemeriksaan status lisensi.',
            'canonical' => route('katalog'),
            'json_ld' => [
                self::organizationSchema(),
                self::breadcrumbSchema([
                    ['name' => 'Beranda', 'url' => route('home')],
                    ['name' => 'Katalog', 'url' => route('katalog')],
                ]),
            ],
        ]);
    }

    public static function book(Book $book): array
    {
        $author = $book->author?->name;
        $title = $author
            ? $book->title.' — '.$author.' | libfree'
            : $book->title.' — libfree';

        return self::build([
            'title' => $title,
            'description' => self::excerpt($book->synopsis),
            'canonical' => route('buku.show', $book->slug),
            'og_type' => 'book',
            'image' => self::bookImage($book),
            'json_ld' => [
                self::organizationSchema(),
                self::bookSchema($book),
                self::breadcrumbSchema([
                    ['name' => 'Beranda', 'url' => route('home')],
                    ['name' => 'Katalog', 'url' => route('katalog')],
                    ['name' => $book->title, 'url' => route('buku.show', $book->slug)],
                ]),
            ],
        ]);
    }

    public static function reader(Book $book): array
    {
        return self::build([
            'title' => 'Baca '.$book->title.' — libfree',
            'description' => 'Baca '.$book->title.' langsung di peramban. Naskah tersedia untuk pembaca yang sudah masuk ke akun libfree.',
            // The reader duplicates the book page; keep the canonical on the
            // detail page and keep the reader itself out of the index.
            'canonical' => route('buku.show', $book->slug),
            'robots' => 'noindex,follow',
            'json_ld' => [],
        ]);
    }

    public static function legal(string $doc): array
    {
        [$title, $description, $route] = match ($doc) {
            'privacy' => [
                'Kebijakan Privasi — libfree',
                'Bagaimana libfree mengumpulkan, memakai, dan melindungi data pembaca, termasuk cookie sesi dan surel akun.',
                'legal.privacy',
            ],
            'terms' => [
                'Syarat & Ketentuan — libfree',
                'Aturan penggunaan layanan libfree, hak dan kewajiban pembaca, serta ketentuan konten dan akun.',
                'legal.terms',
            ],
            default => [
                'Lisensi & Hak Cipta — libfree',
                'Panduan lisensi libfree: domain publik, Creative Commons, verifikasi sumber, dan prosedur pengajuan takedown.',
                'legal.license',
            ],
        };

        return self::build([
            'title' => $title,
            'description' => $description,
            'canonical' => route($route),
            'json_ld' => [
                self::organizationSchema(),
                self::breadcrumbSchema([
                    ['name' => 'Beranda', 'url' => route('home')],
                    ['name' => Str::before($title, ' —'), 'url' => route($route)],
                ]),
            ],
        ]);
    }

    /**
     * Pages that must not appear in search results (auth, reader, admin,
     * upload, personal shelf).
     */
    public static function noindex(string $title, string $description, ?string $canonical = null): array
    {
        return self::build([
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => 'noindex,nofollow',
            'json_ld' => [],
        ]);
    }

    public static function notFound(): array
    {
        return self::build([
            'title' => 'Halaman Tidak Ditemukan — libfree',
            'description' => 'Halaman atau naskah yang kamu cari tidak tersedia di libfree.',
            'robots' => 'noindex,nofollow',
            'json_ld' => [],
        ]);
    }

    private static function build(array $data): array
    {
        $title = $data['title'] ?? self::DEFAULT_TITLE;
        $description = $data['description'] ?? self::DEFAULT_DESCRIPTION;
        $canonical = $data['canonical'] ?? null;
        $image = $data['image'] ?? asset('og-libfree.png');

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $data['robots'] ?? 'index,follow',
            'image' => $image,
            'og' => [
                'type' => $data['og_type'] ?? 'website',
                'title' => $title,
                'description' => $description,
                'url' => $canonical,
                'image' => $image,
                'site_name' => self::SITE_NAME,
                'locale' => 'id_ID',
            ],
            'twitter' => [
                'card' => 'summary_large_image',
                'title' => $title,
                'description' => $description,
                'image' => $image,
            ],
            'json_ld' => array_values(array_filter($data['json_ld'] ?? [self::organizationSchema()])),
        ];
    }

    private static function organizationSchema(): array
    {
        return self::prune([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => self::SITE_NAME,
            'url' => route('home'),
            'logo' => asset('favicon.ico'),
            'email' => 'halo@libfree.id',
            'description' => 'Perpustakaan digital terbuka yang mengkurasi karya domain publik dan berlisensi terbuka untuk pembaca Indonesia.',
        ]);
    }

    private static function websiteSchema(): array
    {
        return self::prune([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => self::SITE_NAME,
            'url' => route('home'),
            'inLanguage' => 'id-ID',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('katalog').'?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ]);
    }

    private static function bookSchema(Book $book): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $book->title,
            'url' => route('buku.show', $book->slug),
            'author' => $book->author ? [
                '@type' => 'Person',
                'name' => $book->author->name,
            ] : null,
            'description' => $book->synopsis,
            'inLanguage' => 'id-ID',
            'bookFormat' => 'https://schema.org/EBook',
            'datePublished' => $book->year ? (string) $book->year : null,
            'publisher' => [
                '@type' => 'Organization',
                'name' => self::SITE_NAME,
            ],
            'isAccessibleForFree' => true,
            'license' => $book->license_url,
        ];

        // Only publish aggregate ratings that are backed by real reader rows.
        if ($book->reviews_count > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $book->rating,
                'reviewCount' => $book->reviews_count,
                'bestRating' => 5,
                'worstRating' => 1,
            ];
        }

        return self::prune($schema);
    }

    private static function breadcrumbSchema(array $items): array
    {
        return self::prune([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(fn (array $item, int $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ], $items, array_keys($items)),
        ]);
    }

    private static function bookImage(Book $book): string
    {
        if ($book->cover_path) {
            return url('/api/buku/'.$book->slug.'/sampul');
        }

        return asset('og-libfree.png');
    }

    private static function excerpt(?string $text): string
    {
        $plain = trim(strip_tags((string) $text));

        return $plain === ''
            ? self::DEFAULT_DESCRIPTION
            : Str::limit($plain, 155);
    }

    /**
     * Remove empty nested values so JSON-LD only contains valid fields.
     */
    private static function prune(array $schema): array
    {
        foreach ($schema as $key => $value) {
            if (is_array($value)) {
                $value = self::prune($value);
            }

            if ($value === null || $value === '' || $value === []) {
                unset($schema[$key]);

                continue;
            }

            $schema[$key] = $value;
        }

        return $schema;
    }
}

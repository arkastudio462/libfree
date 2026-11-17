<?php

namespace App\Support;

use App\Models\Author;
use Illuminate\Support\Str;

class BookPresentation
{
    /**
     * Category palettes shared by the seeder and the publishing pipeline.
     *
     * @var array<string, array<string, string>>
     */
    public const THEMES = [
        'sastra-klasik' => [
            'badge' => 'Sastra Klasik',
            'badge_class' => 'bg-brand-yellow text-brand-dark',
            'cover_class' => 'bg-brand-peach',
            'spine_kicker' => 'Sastra Klasik',
            'text_class' => 'text-brand-coral',
            'hex' => '#9C3826',
            'emoji' => '🖋️',
            'button' => 'hover:bg-brand-coral',
        ],
        'sejarah-biografi' => [
            'badge' => 'Sejarah',
            'badge_class' => 'bg-brand-blue-light text-brand-dark',
            'cover_class' => 'bg-brand-blue-light',
            'spine_kicker' => 'Sejarah Nusantara',
            'text_class' => 'text-brand-blue',
            'hex' => '#1F4E6B',
            'emoji' => '🏛️',
            'button' => 'hover:bg-brand-blue',
        ],
        'sains-matematika' => [
            'badge' => 'Sains',
            'badge_class' => 'bg-brand-green-light text-brand-dark',
            'cover_class' => 'bg-brand-green-light',
            'spine_kicker' => 'Sains & Matematika',
            'text_class' => 'text-brand-green',
            'hex' => '#2F6B4F',
            'emoji' => '🔬',
            'button' => 'hover:bg-brand-green',
        ],
        'filsafat-pemikiran' => [
            'badge' => 'Filsafat',
            'badge_class' => 'bg-brand-blue-light text-brand-dark',
            'cover_class' => 'bg-brand-lavender',
            'spine_kicker' => 'Pemikiran Nusantara',
            'text_class' => 'text-brand-blue',
            'hex' => '#5B4B8A',
            'emoji' => '🧠',
            'button' => 'hover:bg-brand-blue',
        ],
        'cerita-rakyat-mitos' => [
            'badge' => 'Cerita Rakyat',
            'badge_class' => 'bg-brand-green-light text-brand-dark',
            'cover_class' => 'bg-brand-yellow-light',
            'spine_kicker' => 'Cerita Rakyat',
            'text_class' => 'text-brand-green',
            'hex' => '#6B4E12',
            'emoji' => '🦜',
            'button' => 'hover:bg-brand-green',
        ],
        'naskah-kuno-lontar' => [
            'badge' => 'Naskah Kuno',
            'badge_class' => 'bg-brand-green-light text-brand-dark',
            'cover_class' => 'bg-brand-yellow-light',
            'spine_kicker' => 'Naskah Kuno',
            'text_class' => 'text-brand-green',
            'hex' => '#7A4B12',
            'emoji' => '📜',
            'button' => 'hover:bg-brand-yellow',
        ],
    ];

    /**
     * Resolve the palette for a category slug.
     *
     * @return array<string, string>
     */
    public static function theme(string $categorySlug): array
    {
        return self::THEMES[$categorySlug] ?? self::THEMES['sastra-klasik'];
    }

    /**
     * Build catalog-card presentation data for a book.
     *
     * @param  array<int, string>  $formats
     */
    public static function card(string $title, array $formats, Author $author, string $categorySlug): array
    {
        $theme = self::theme($categorySlug);

        return [
            'badge' => $theme['badge'],
            'badge_class' => $theme['badge_class'],
            'cover_class' => $theme['cover_class'],
            'spine_rotate' => 'group-hover:rotate-1',
            'spine_kicker' => $theme['spine_kicker'],
            'spine_kicker_class' => $theme['text_class'],
            'spine_title' => Str::upper($title),
            'spine_title_class' => Str::length($title) > 18 ? 'text-[10px]' : 'text-xs',
            'spine_author' => $author->short_name ?: $author->name,
            'author_label' => $author->name,
            'author_label_class' => $theme['text_class'],
            'formats_label' => collect($formats)->map(fn (string $format) => Str::upper($format))->implode(' • '),
            'related_badge' => $theme['badge'],
            'related_cover_class' => $theme['cover_class'],
            'related_hover_class' => 'group-hover:'.$theme['text_class'],
            'related_button_class' => $theme['button'],
        ];
    }

    /**
     * Build an editorial reader preview for a newly approved title.
     *
     * @return array<int, array{title: string, paragraphs: array<int, string>}>
     */
    public static function readerPreview(string $title, string $synopsis): array
    {
        return [
            [
                'title' => 'Bab 1 — Pengantar Arsip',
                'paragraphs' => [
                    $synopsis,
                    'Pratinjau ini disusun oleh tim kurator libfree. Berkas naskah lengkap tersedia melalui unduhan resmi pada halaman detail buku.',
                ],
            ],
            [
                'title' => 'Bab 2 — Catatan Kurator',
                'paragraphs' => [
                    'Judul '.$title.' dipublikasikan setelah melewati pemeriksaan sumber dan status lisensi.',
                    'Gunakan fitur penanda dan Rak Baca untuk menyimpan bagian yang ingin ditinjau kembali.',
                ],
            ],
        ];
    }

    /**
     * Source search URL used until a canonical manuscript URL is recorded.
     */
    public static function sourceUrl(string $title): string
    {
        return 'https://id.wikisource.org/w/index.php?search='.rawurlencode($title);
    }
}

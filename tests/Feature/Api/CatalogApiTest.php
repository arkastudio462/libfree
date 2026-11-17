<?php

namespace Tests\Feature\Api;

use App\Models\Author;
use App\Models\Book;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);
    }

    public function test_index_returns_paginated_books(): void
    {
        $response = $this->getJson('/api/buku');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'title', 'slug', 'author', 'category', 'card', 'showcase']],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);

        $this->assertSame(15, $response->json('meta.total'));
        $this->assertCount(12, $response->json('data'));
    }

    public function test_index_caps_per_page_at_100(): void
    {
        $response = $this->getJson('/api/buku?per_page=1000');

        $response->assertOk();
        $this->assertSame(100, $response->json('meta.per_page'));
    }

    public function test_index_filters_by_minimum_rating(): void
    {
        $response = $this->getJson('/api/buku?min_rating=4.8')->assertOk();

        $this->assertLessThan(15, $response->json('meta.total'));

        foreach ($response->json('data') as $book) {
            $this->assertGreaterThanOrEqual(4.8, $book['rating']);
        }
    }

    public function test_index_filters_by_category(): void
    {
        $response = $this->getJson('/api/buku?kategori=filsafat-pemikiran');

        $response->assertOk();
        $this->assertSame(2, $response->json('meta.total'));

        foreach ($response->json('data') as $book) {
            $this->assertSame('filsafat-pemikiran', $book['category']['slug']);
        }
    }

    public function test_index_filters_by_format(): void
    {
        $all = $this->getJson('/api/buku?format=')->assertOk();
        $pdfOnly = $this->getJson('/api/buku?format=pdf')->assertOk();

        $this->assertSame(15, $all->json('meta.total'));
        $this->assertLessThan(15, $pdfOnly->json('meta.total'));

        foreach ($pdfOnly->json('data') as $book) {
            $this->assertContains('pdf', $book['formats']);
        }
    }

    public function test_search_matches_title_author_and_category(): void
    {
        $single = $this->getJson('/api/buku?q=Multatuli')->assertOk();
        $this->assertSame(1, $single->json('meta.total'));
        $this->assertSame('Max Havelaar', $single->json('data.0.title'));

        // Multi-word terms use OR semantics between tokens (M1.8 quick-tags).
        $novel = $this->getJson('/api/buku?q=Novel Multatuli')->assertOk();
        $this->assertSame(1, $novel->json('meta.total'));
        $this->assertSame('Max Havelaar', $novel->json('data.0.title'));

        $philosophy = $this->getJson('/api/buku?q=Filsafat')->assertOk();
        $this->assertSame(2, $philosophy->json('meta.total'));

        $folk = $this->getJson('/api/buku?q=Cerita Rakyat')->assertOk();
        $this->assertSame(4, $folk->json('meta.total'));

        $empty = $this->getJson('/api/buku?q=Sains Populer')->assertOk();
        $this->assertSame(0, $empty->json('meta.total'));
        $this->assertSame([], $empty->json('data'));
    }

    public function test_search_matches_category_name(): void
    {
        $response = $this->getJson('/api/buku?q=Sejarah Nusantara')->assertOk();

        $this->assertGreaterThanOrEqual(3, $response->json('meta.total'));

        $titles = array_column($response->json('data'), 'title');
        $this->assertContains('Sejarah Melayu', $titles);
        $this->assertContains('Babad Tanah Jawi', $titles);
    }

    public function test_sort_options_order_the_results(): void
    {
        $alphabetical = $this->getJson('/api/buku?sort=judul')->assertOk();
        $titles = array_column($alphabetical->json('data'), 'title');
        $sorted = $titles;
        sort($sorted);
        $this->assertSame($sorted, $titles);

        $rating = $this->getJson('/api/buku?sort=rating')->assertOk();
        $ratings = array_column($rating->json('data'), 'rating');
        $descending = $ratings;
        rsort($descending);
        $this->assertSame($descending, $ratings);

        $newest = $this->getJson('/api/buku?sort=terbaru')->assertOk();
        $years = array_column($newest->json('data'), 'year');
        $descendingYears = $years;
        rsort($descendingYears);
        $this->assertSame($descendingYears, $years);
    }

    public function test_pagination_walks_through_pages(): void
    {
        $page1 = $this->getJson('/api/buku?per_page=12&page=1')->assertOk();
        $page2 = $this->getJson('/api/buku?per_page=12&page=2')->assertOk();

        $this->assertSame(1, $page1->json('meta.current_page'));
        $this->assertSame(2, $page2->json('meta.current_page'));
        $this->assertSame(2, $page2->json('meta.last_page'));
        $this->assertCount(12, $page1->json('data'));
        $this->assertCount(3, $page2->json('data'));

        $overlap = array_intersect(
            array_column($page1->json('data'), 'id'),
            array_column($page2->json('data'), 'id'),
        );
        $this->assertSame([], array_values($overlap));
    }

    public function test_show_returns_detail_license_related_and_reviews(): void
    {
        $response = $this->getJson('/api/buku/max-havelaar');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'title', 'slug', 'quote', 'sizes', 'author', 'category',
                    'license' => ['status', 'name', 'url', 'source_url', 'verified', 'audited_at'],
                    'detail' => ['cover', 'cover_badges', 'metrics', 'tabs', 'specs', 'downloads', 'distribution', 'series', 'license'],
                    'related' => [['slug']],
                    'reviews' => [['name', 'rating', 'body']],
                ],
            ]);

        $this->assertSame('MAX', $response->json('data.detail.cover.title'));
        $this->assertSame('HAVELAAR', $response->json('data.detail.cover.title_accent'));
        $this->assertSame('public_domain', $response->json('data.license.status'));
        $this->assertTrue($response->json('data.license.verified'));
        $this->assertNotNull($response->json('data.license.source_url'));
        $this->assertCount(3, $response->json('data.reviews'));

        // Kategori Sastra Klasik berisi tiga judul, jadi dua buku terkait.
        $this->assertCount(2, $response->json('data.related'));

        foreach ($response->json('data.related') as $related) {
            $this->assertNotSame('max-havelaar', $related['slug']);
        }
    }

    public function test_show_falls_back_for_books_without_stored_detail(): void
    {
        $response = $this->getJson('/api/buku/hikayat-abdullah');

        $response->assertOk();

        $detail = $response->json('data.detail');
        $this->assertNotNull($detail);
        $this->assertSame('Sinopsis Lengkap', $detail['tabs'][0]['label']);
        $this->assertNotEmpty($detail['metrics']);
        // Buku contoh belum punya berkas naskah, jadi belum ada unduhan.
        $this->assertSame([], $detail['downloads']);
        $this->assertNull($response->json('data.quote'));
        $this->assertNotEmpty($detail['specs']);
    }

    public function test_show_exposes_a_download_once_a_manuscript_is_attached(): void
    {
        $book = Book::query()->where('slug', 'hikayat-abdullah')->firstOrFail();
        $book->update([
            'manuscript_path' => 'manuscripts/hikayat-abdullah.pdf',
            'manuscript_name' => 'hikayat-abdullah.pdf',
            'manuscript_size' => 1024,
            'manuscript_mime' => 'application/pdf',
        ]);

        $downloads = $this->getJson('/api/buku/hikayat-abdullah')
            ->assertOk()
            ->json('data.detail.downloads');

        $this->assertCount(1, $downloads);
        $this->assertStringStartsWith('PDF', $downloads[0]['label']);
        $this->assertStringContainsString('/api/buku/hikayat-abdullah/unduh', $downloads[0]['url']);
    }

    public function test_show_returns_404_for_unknown_slug(): void
    {
        $this->getJson('/api/buku/judul-yang-tidak-ada')->assertNotFound();
    }

    public function test_unpublished_books_are_hidden_from_the_public_catalog(): void
    {
        $book = Book::query()->where('slug', 'max-havelaar')->firstOrFail();
        $book->update(['is_published' => false]);

        $this->getJson('/api/buku')->assertOk()->assertJsonPath('meta.total', 14);
        $this->getJson('/api/buku/'.$book->slug)->assertNotFound();
        $this->getJson('/api/buku/'.$book->slug.'/baca')->assertNotFound();
        $this->get('/buku/'.$book->slug)->assertNotFound();
        $this->get('/baca/'.$book->slug)->assertNotFound();
    }

    public function test_category_index_returns_canonical_categories_with_counts(): void
    {
        $response = $this->getJson('/api/kategori');

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'books_count']]]);

        $this->assertCount(6, $response->json('data'));

        $bySlug = collect($response->json('data'))->keyBy('slug');
        $this->assertSame(0, $bySlug['sains-matematika']['books_count']);
        $this->assertSame(2, $bySlug['filsafat-pemikiran']['books_count']);
        $this->assertSame(3, $bySlug['sastra-klasik']['books_count']);
        $this->assertSame(4, $bySlug['cerita-rakyat-mitos']['books_count']);

        $total = collect($response->json('data'))->sum('books_count');
        $this->assertSame(15, $total);
    }

    public function test_category_counts_ignore_unpublished_books(): void
    {
        Book::query()->where('slug', 'madilog')->firstOrFail()->update(['is_published' => false]);

        $response = $this->getJson('/api/kategori')->assertOk();
        $bySlug = collect($response->json('data'))->keyBy('slug');

        $this->assertSame(1, $bySlug['filsafat-pemikiran']['books_count']);
    }

    public function test_favorite_authors_are_ranked(): void
    {
        $response = $this->getJson('/api/penulis?favorit=1');

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id', 'name', 'favorite_rank', 'books_count']]]);

        $this->assertCount(4, $response->json('data'));
        $this->assertSame('Multatuli', $response->json('data.0.name'));
        $this->assertSame(1, $response->json('data.0.favorite_rank'));
        $this->assertSame([1, 2, 3, 4], array_column($response->json('data'), 'favorite_rank'));
    }

    public function test_favorite_authors_without_published_books_are_hidden(): void
    {
        $author = Author::query()->where('slug', 'mpu-prapanca')->firstOrFail();
        Book::query()->where('author_id', $author->id)->update(['is_published' => false]);

        $response = $this->getJson('/api/penulis?favorit=1')->assertOk();

        $this->assertCount(3, $response->json('data'));
        $this->assertNotContains('Mpu Prapanca', array_column($response->json('data'), 'name'));
    }
}

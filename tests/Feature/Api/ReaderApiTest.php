<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReaderApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);
    }

    public function test_reader_returns_chapters_for_a_seeded_book(): void
    {
        $book = Book::query()->firstOrFail();

        $response = $this->getJson('/api/buku/'.$book->slug.'/baca');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'book' => ['id', 'title', 'slug', 'pages', 'reader_available', 'author', 'category'],
                    'chapters' => [['number', 'title', 'paragraphs']],
                    'total_chapters',
                ],
            ])
            ->assertJsonPath('data.book.slug', $book->slug)
            ->assertJsonPath('data.book.reader_available', true)
            ->assertJsonPath('data.book.has_manuscript', false);

        $this->assertGreaterThan(0, $response->json('data.total_chapters'));
        $this->assertNotEmpty($response->json('data.chapters.0.paragraphs'));
    }

    public function test_book_detail_exposes_reader_availability_without_content(): void
    {
        $book = Book::query()->firstOrFail();

        $response = $this->getJson('/api/buku/'.$book->slug)->assertOk();

        $response->assertJsonPath('data.reader_available', true);
        $this->assertArrayNotHasKey('reader_content', $response->json('data'));
    }

    public function test_catalog_listing_never_ships_reader_content(): void
    {
        $response = $this->getJson('/api/buku?per_page=100')->assertOk();

        $this->assertArrayNotHasKey('reader_content', $response->json('data.0'));
    }

    public function test_reader_returns_404_for_an_unknown_slug(): void
    {
        $this->getJson('/api/buku/judul-yang-tidak-ada/baca')->assertNotFound();
    }

    public function test_reader_exposes_manuscript_metadata_for_an_attached_pdf(): void
    {
        Storage::fake('local');

        $book = Book::query()->firstOrFail();
        $path = 'manuscripts/'.$book->slug.'.pdf';

        Storage::disk('local')->put($path, '%PDF-1.4 contoh naskah');

        $book->update([
            'manuscript_path' => $path,
            'manuscript_name' => 'naskah-asli.pdf',
            'manuscript_size' => 4096,
            'manuscript_mime' => 'application/pdf',
            'reader_content' => null,
        ]);

        $this->getJson('/api/buku/'.$book->slug.'/baca')
            ->assertOk()
            ->assertJsonPath('data.book.has_manuscript', true)
            ->assertJsonPath('data.book.reader_available', true)
            ->assertJsonPath('data.book.manuscript.format', 'pdf')
            ->assertJsonPath('data.book.manuscript.url', '/api/buku/'.$book->slug.'/berkas')
            ->assertJsonPath('data.book.manuscript.name', 'naskah-asli.pdf')
            ->assertJsonPath('data.book.manuscript.mime', 'application/pdf')
            ->assertJsonPath('data.total_chapters', 0);

        // The detail page must still offer the reader when only a manuscript exists.
        $this->getJson('/api/buku/'.$book->slug)
            ->assertOk()
            ->assertJsonPath('data.reader_available', true);
    }

    public function test_reader_ignores_manuscripts_it_cannot_render(): void
    {
        Storage::fake('local');

        $book = Book::query()->firstOrFail();

        Storage::disk('local')->put('manuscripts/naskah.txt', 'teks biasa');
        $book->update([
            'manuscript_path' => 'manuscripts/naskah.txt',
            'manuscript_name' => 'naskah.txt',
            'manuscript_size' => 11,
            'manuscript_mime' => 'text/plain',
        ]);

        $this->getJson('/api/buku/'.$book->slug.'/baca')
            ->assertOk()
            ->assertJsonPath('data.book.has_manuscript', false)
            ->assertJsonPath('data.book.manuscript', null);
    }

    public function test_manuscript_stream_is_inline_with_range_support(): void
    {
        Storage::fake('local');

        $book = Book::query()->firstOrFail();
        $path = 'manuscripts/stream.pdf';
        $content = '%PDF-1.4'.str_repeat('A', 1000);

        Storage::disk('local')->put($path, $content);
        $book->update([
            'manuscript_path' => $path,
            'manuscript_name' => 'stream.pdf',
            'manuscript_size' => strlen($content),
            'manuscript_mime' => 'application/pdf',
        ]);

        $response = $this->get('/api/buku/'.$book->slug.'/berkas');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $this->assertStringStartsWith('inline;', (string) $response->headers->get('Content-Disposition'));
        $this->assertSame($content, $response->getFile()->getContent());

        $range = $this->withHeaders(['Range' => 'bytes=0-3'])->get('/api/buku/'.$book->slug.'/berkas');

        $range->assertStatus(206)
            ->assertHeader('Content-Range', 'bytes 0-3/'.strlen($content))
            ->assertHeader('Content-Length', '4');
    }

    public function test_inline_stream_hides_missing_or_unpublished_manuscripts(): void
    {
        Storage::fake('local');

        $book = Book::query()->firstOrFail();

        $this->get('/api/buku/'.$book->slug.'/berkas')->assertNotFound();

        Storage::disk('local')->put('manuscripts/tersembunyi.pdf', '%PDF-1.4');
        $book->update([
            'manuscript_path' => 'manuscripts/tersembunyi.pdf',
            'manuscript_name' => 'tersembunyi.pdf',
            'manuscript_size' => 8,
            'manuscript_mime' => 'application/pdf',
            'is_published' => false,
        ]);

        $this->get('/api/buku/'.$book->slug.'/berkas')->assertNotFound();
    }
}

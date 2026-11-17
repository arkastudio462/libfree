<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\User;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->seed(CatalogSeeder::class);
    }

    private static function fakeCover(string $name): UploadedFile
    {
        $jpeg = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/wAALCAABAAEBAREA/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAD8AVN//2Q==');

        return UploadedFile::fake()->createWithContent($name, $jpeg);
    }

    public function test_guests_and_readers_cannot_use_the_content_audit(): void
    {
        $this->getJson('/api/admin/konten')->assertUnauthorized();

        $book = Book::query()->firstOrFail();
        $this->patchJson('/api/admin/konten/'.$book->id, ['is_published' => false])->assertUnauthorized();

        $reader = User::factory()->create();

        $this->actingAs($reader)->getJson('/api/admin/konten')->assertForbidden();
        $this->actingAs($reader)
            ->patchJson('/api/admin/konten/'.$book->id, ['is_published' => false])
            ->assertForbidden();
    }

    public function test_admin_can_list_books_with_audit_metadata(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->getJson('/api/admin/konten')->assertOk();

        $response->assertJsonStructure([
            'data' => [[
                'id', 'title', 'slug', 'license_status', 'license_name',
                'source_url', 'audit_notes', 'is_published', 'published_at', 'audited_at',
            ]],
            'meta' => ['total', 'published', 'draft', 'unverified', 'current_page', 'last_page'],
        ]);

        $this->assertSame(15, $response->json('meta.total'));
        $this->assertSame(15, $response->json('meta.published'));
        $this->assertSame(0, $response->json('meta.draft'));
    }

    public function test_admin_can_unpublish_and_republish_a_book(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $book = Book::query()->where('slug', 'max-havelaar')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson('/api/admin/konten/'.$book->id, ['is_published' => false])
            ->assertOk()
            ->assertJsonPath('data.is_published', false);

        $this->getJson('/api/buku/max-havelaar')->assertNotFound();
        $this->getJson('/api/buku')->assertJsonPath('meta.total', 14);

        $this->actingAs($admin)
            ->patchJson('/api/admin/konten/'.$book->id, ['is_published' => true])
            ->assertOk()
            ->assertJsonPath('data.is_published', true);

        $this->getJson('/api/buku/max-havelaar')->assertOk();
    }

    public function test_book_cannot_be_published_without_a_verified_license(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $book = Book::query()->where('slug', 'max-havelaar')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson('/api/admin/konten/'.$book->id, [
                'license_status' => 'unverified',
                'is_published' => true,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('license_status');

        $this->actingAs($admin)
            ->patchJson('/api/admin/konten/'.$book->id, [
                'license_status' => 'restricted',
                'is_published' => true,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('license_status');

        $this->getJson('/api/buku/max-havelaar')->assertOk();
    }

    public function test_book_cannot_be_published_without_a_source_url(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $book = Book::query()->where('slug', 'max-havelaar')->firstOrFail();

        $book->update(['source_url' => null]);

        $this->actingAs($admin)
            ->patchJson('/api/admin/konten/'.$book->id, [
                'license_status' => 'public_domain',
                'is_published' => true,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('source_url');
    }

    public function test_admin_can_record_license_evidence_and_audit_notes(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $book = Book::query()->where('slug', 'max-havelaar')->firstOrFail();

        $this->actingAs($admin)
            ->patchJson('/api/admin/konten/'.$book->id, [
                'license_status' => 'public_domain',
                'license_name' => 'Domain Publik (wafat 1887)',
                'license_url' => 'https://creativecommons.org/publicdomain/mark/1.0/',
                'source_url' => 'https://id.wikisource.org/w/index.php?search=Max+Havelaar',
                'audit_notes' => 'Diperiksa ulang oleh kurator: penulis wafat 1887.',
                'is_published' => true,
            ])
            ->assertOk()
            ->assertJsonPath('data.license_status', 'public_domain')
            ->assertJsonPath('data.license_name', 'Domain Publik (wafat 1887)')
            ->assertJsonPath('data.audit_notes', 'Diperiksa ulang oleh kurator: penulis wafat 1887.')
            ->assertJsonPath('data.is_published', true);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'license_status' => 'public_domain',
            'license_name' => 'Domain Publik (wafat 1887)',
        ]);
    }

    public function test_admin_can_upload_manuscript_and_cover_files(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $book = Book::query()->where('slug', 'madilog')->firstOrFail();

        $response = $this->actingAs($admin)
            ->post('/api/admin/konten/'.$book->id.'/berkas', [
                'manuscript' => UploadedFile::fake()->create('madilog.epub', 240, 'application/epub+zip'),
                'cover' => self::fakeCover('madilog.jpg'),
            ], ['Accept' => 'application/json']);

        $response->assertOk()
            ->assertJsonPath('data.has_manuscript', true)
            ->assertJsonPath('data.manuscript_format', 'epub')
            ->assertJsonPath('data.has_cover', true);

        $book->refresh();

        $this->assertNotNull($book->manuscript_path);
        $this->assertNotNull($book->cover_path);
        $this->assertSame('epub', $book->manuscriptFormat());
        Storage::disk('local')->assertExists($book->manuscript_path);
        Storage::disk('local')->assertExists($book->cover_path);

        // Unduhan publik baru tersedia karena buku ini sudah tayang.
        $this->get('/api/buku/'.$book->slug.'/unduh')->assertOk();
        $this->get('/api/buku/'.$book->slug.'/sampul')->assertOk();
        $this->get('/api/admin/konten/'.$book->id.'/sampul')
            ->assertOk();
    }

    public function test_file_upload_requires_at_least_one_file(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $book = Book::query()->firstOrFail();

        $this->actingAs($admin)
            ->post('/api/admin/konten/'.$book->id.'/berkas', [], ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('manuscript');
    }

    public function test_admin_can_filter_the_audit_queue(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $book = Book::query()->where('slug', 'madilog')->firstOrFail();
        $book->update(['is_published' => false, 'license_status' => 'unverified']);

        $this->actingAs($admin)
            ->getJson('/api/admin/konten?status=draft')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'madilog');

        $this->actingAs($admin)
            ->getJson('/api/admin/konten?status=unverified')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);

        $this->actingAs($admin)
            ->getJson('/api/admin/konten?q=Madilog')
            ->assertOk()
            ->assertJsonPath('meta.total', 1);
    }
}

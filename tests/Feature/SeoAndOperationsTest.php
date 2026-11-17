<?php

namespace Tests\Feature;

use App\Models\Book;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeoAndOperationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);
    }

    public function test_home_renders_metadata_and_organization_json_ld(): void
    {
        $response = $this->get('/')->assertOk();

        $response->assertSee('<title>libfree — Baca Buku Klasik Gratis &amp; Legal</title>', false)
            ->assertSee('name="description"', false)
            ->assertSee('rel="canonical"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('name="twitter:card"', false)
            ->assertSee('"@type":"Organization"', false)
            ->assertSee('"@type":"WebSite"', false)
            ->assertSee('name="robots" content="index,follow"', false);
    }

    public function test_book_page_renders_book_json_ld_and_canonical(): void
    {
        $response = $this->get('/buku/max-havelaar')->assertOk();

        $response->assertSee('Max Havelaar', false)
            ->assertSee('"@type":"Book"', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('/buku/max-havelaar', false)
            ->assertSee('property="og:type" content="book"', false);
    }

    public function test_auth_and_personal_pages_are_noindex(): void
    {
        foreach (['/masuk', '/daftar', '/lupa-sandi', '/atur-ulang-sandi', '/rak', '/unggah', '/admin/konten'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('name="robots" content="noindex,nofollow"', false);
        }
    }

    public function test_legal_pages_render_their_own_metadata(): void
    {
        $this->get('/kebijakan-privasi')
            ->assertOk()
            ->assertSee('<title>Kebijakan Privasi — libfree</title>', false);

        $this->get('/syarat-ketentuan')
            ->assertOk()
            ->assertSee('<title>Syarat &amp; Ketentuan — libfree</title>', false);

        $this->get('/lisensi')
            ->assertOk()
            ->assertSee('<title>Lisensi &amp; Hak Cipta — libfree</title>', false);
    }

    public function test_unknown_pages_return_noindex_404(): void
    {
        $this->get('/halaman-tidak-ada')
            ->assertNotFound()
            ->assertSee('name="robots" content="noindex,nofollow"', false)
            ->assertSee('Halaman Tidak Ditemukan — libfree', false);
    }

    public function test_reader_is_noindex_and_canonical_to_the_book_page(): void
    {
        $this->get('/baca/max-havelaar')
            ->assertOk()
            ->assertSee('name="robots" content="noindex,follow"', false)
            ->assertSee('/buku/max-havelaar', false);
    }

    public function test_sitemap_lists_only_published_books(): void
    {
        Book::query()->where('slug', 'hikayat-abdullah')->update(['is_published' => false]);

        $response = $this->get('/sitemap.xml')->assertOk();

        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('/buku/max-havelaar', false)
            ->assertDontSee('/buku/hikayat-abdullah', false)
            ->assertSee(route('katalog'), false);
    }

    public function test_robots_file_points_to_the_sitemap_and_hides_private_areas(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin/', false)
            ->assertSee('Disallow: /api/', false)
            ->assertSee('Sitemap: '.url('/sitemap.xml'), false);
    }

    public function test_security_headers_are_present(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
    }

    public function test_health_endpoint_checks_database_cache_and_storage(): void
    {
        Storage::fake('local');

        $this->getJson('/health')
            ->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('checks.database', 'ok')
            ->assertJsonPath('checks.cache', 'ok')
            ->assertJsonPath('checks.storage', 'ok');
    }
}

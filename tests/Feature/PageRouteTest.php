<?php

namespace Tests\Feature;

use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);
    }

    public function test_known_spa_routes_render_the_app_shell(): void
    {
        foreach (['/', '/katalog', '/masuk', '/daftar', '/rak', '/kebijakan-privasi', '/syarat-ketentuan', '/lisensi', '/unggah', '/admin/unggahan', '/admin/konten', '/buku/max-havelaar', '/baca/max-havelaar'] as $path) {
            $this->get($path)->assertOk()->assertSee('id="app"', false);
        }
    }

    public function test_unknown_urls_return_a_404_shell(): void
    {
        $this->get('/halaman-yang-tidak-ada')->assertNotFound()->assertSee('id="app"', false);
    }

    public function test_unknown_book_slugs_return_a_404_shell(): void
    {
        $this->get('/buku/judul-yang-tidak-ada')->assertNotFound()->assertSee('id="app"', false);
        $this->get('/baca/judul-yang-tidak-ada')->assertNotFound()->assertSee('id="app"', false);
    }
}

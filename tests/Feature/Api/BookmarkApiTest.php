<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\User;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarkApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);
    }

    public function test_guests_cannot_use_the_shelf(): void
    {
        $this->getJson('/api/rak')->assertUnauthorized();

        $book = Book::query()->firstOrFail();

        $this->postJson('/api/buku/'.$book->slug.'/bookmark')->assertUnauthorized();
    }

    public function test_reader_can_add_and_remove_a_bookmark(): void
    {
        $user = User::factory()->create();
        $book = Book::query()->firstOrFail();
        $initialCount = $book->bookmark_count;

        $this->actingAs($user)
            ->postJson('/api/buku/'.$book->slug.'/bookmark')
            ->assertCreated()
            ->assertJsonPath('data.bookmarked', true)
            ->assertJsonPath('data.bookmark_count', $initialCount + 1);

        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $this->actingAs($user)
            ->getJson('/api/buku/'.$book->slug)
            ->assertOk()
            ->assertJsonPath('data.is_bookmarked', true);

        $this->actingAs($user)
            ->getJson('/api/buku/'.$book->slug.'/bookmark')
            ->assertOk()
            ->assertJsonPath('data.bookmarked', true);

        $this->actingAs($user)
            ->deleteJson('/api/buku/'.$book->slug.'/bookmark')
            ->assertOk()
            ->assertJsonPath('data.bookmarked', false)
            ->assertJsonPath('data.bookmark_count', $initialCount);

        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_bookmarking_twice_is_idempotent(): void
    {
        $user = User::factory()->create();
        $book = Book::query()->firstOrFail();
        $initialCount = $book->bookmark_count;

        $this->actingAs($user)->postJson('/api/buku/'.$book->slug.'/bookmark')->assertCreated();
        $this->actingAs($user)->postJson('/api/buku/'.$book->slug.'/bookmark')->assertOk();

        $this->assertSame(1, $user->bookmarks()->count());
        $this->assertSame($initialCount + 1, $book->fresh()->bookmark_count);
    }

    public function test_shelf_lists_only_the_readers_saved_books(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        [$first, $second, $third] = Book::query()->orderBy('id')->take(3)->get();

        $user->bookmarks()->create(['book_id' => $first->id]);
        $other->bookmarks()->create(['book_id' => $second->id]);
        $user->bookmarks()->create(['book_id' => $third->id]);

        $response = $this->actingAs($user)->getJson('/api/rak');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('data.0.is_bookmarked', true);

        $slugs = collect($response->json('data'))->pluck('slug')->all();

        $this->assertEqualsCanonicalizing([$first->slug, $third->slug], $slugs);
    }

    public function test_catalog_marks_the_readers_bookmarks(): void
    {
        $user = User::factory()->create();
        $book = Book::query()->firstOrFail();

        $user->bookmarks()->create(['book_id' => $book->id]);

        $response = $this->actingAs($user)->getJson('/api/buku?per_page=100');

        $response->assertOk();

        $match = collect($response->json('data'))->firstWhere('id', $book->id);

        $this->assertTrue($match['is_bookmarked']);
    }

    public function test_guests_never_see_bookmark_flags(): void
    {
        $book = Book::query()->firstOrFail();
        User::factory()->create()->bookmarks()->create(['book_id' => $book->id]);

        $response = $this->getJson('/api/buku?per_page=100')->assertOk();
        $match = collect($response->json('data'))->firstWhere('id', $book->id);

        $this->assertFalse($match['is_bookmarked']);
    }
}

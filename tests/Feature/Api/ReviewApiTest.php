<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);
    }

    private function body(string $text = 'Catatan pembaca yang cukup panjang untuk lolos validasi.'): string
    {
        return $text;
    }

    public function test_guests_cannot_publish_reviews(): void
    {
        $this->postJson('/api/buku/max-havelaar/ulasan', [
            'rating' => 5,
            'body' => $this->body(),
        ])->assertUnauthorized();
    }

    public function test_reader_can_publish_a_review_on_a_published_book(): void
    {
        $user = User::factory()->create(['name' => 'Rani Pembaca']);
        $book = Book::query()->where('slug', 'max-havelaar')->firstOrFail();
        $before = $book->reviews_count;

        $response = $this->actingAs($user)
            ->postJson('/api/buku/max-havelaar/ulasan', [
                'rating' => 4,
                'body' => $this->body('Bab pembuka lambat, tetapi kritik sosialnya tetap tajam.'),
            ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Rani Pembaca')
            ->assertJsonPath('data.initials', 'RP')
            ->assertJsonPath('data.rating', 4)
            ->assertJsonPath('data.is_mine', true)
            ->assertJsonPath('meta.reviews_count', $before + 1);

        $this->assertDatabaseHas('reviews', [
            'book_id' => $book->id,
            'user_id' => $user->id,
            'rating' => 4,
        ]);

        $book->refresh();
        $this->assertSame($before + 1, $book->reviews_count);

        // Ulasan pengguna muncul lebih dulu di payload detail.
        $this->actingAs($user)
            ->getJson('/api/buku/max-havelaar')
            ->assertOk()
            ->assertJsonPath('data.reviews.0.name', 'Rani Pembaca')
            ->assertJsonPath('data.reviews.0.is_mine', true);
    }

    public function test_second_submission_updates_the_same_review(): void
    {
        $user = User::factory()->create();
        $book = Book::query()->where('slug', 'max-havelaar')->firstOrFail();
        $before = $book->reviews_count;

        $this->actingAs($user)
            ->postJson('/api/buku/max-havelaar/ulasan', [
                'rating' => 3,
                'body' => $this->body('Kesan pertama cukup menarik untuk dilanjutkan.'),
            ])
            ->assertCreated();

        $this->actingAs($user)
            ->postJson('/api/buku/max-havelaar/ulasan', [
                'rating' => 5,
                'body' => $this->body('Setelah selesai membaca, penilaian saya berubah menjadi lebih baik.'),
            ])
            ->assertOk()
            ->assertJsonPath('meta.reviews_count', $before + 1);

        $this->assertSame(1, Review::query()->where('book_id', $book->id)->where('user_id', $user->id)->count());
        $this->assertSame(5.0, (float) Review::query()->where('user_id', $user->id)->firstOrFail()->rating);
        $this->assertSame($before + 1, $book->refresh()->reviews_count);
    }

    public function test_review_validates_rating_and_body(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/buku/max-havelaar/ulasan', [
                'rating' => 9,
                'body' => 'pendek',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating', 'body']);
    }

    public function test_unpublished_books_cannot_be_reviewed(): void
    {
        $user = User::factory()->create();
        $book = Book::query()->where('slug', 'max-havelaar')->firstOrFail();
        $book->update(['is_published' => false]);

        $this->actingAs($user)
            ->postJson('/api/buku/max-havelaar/ulasan', [
                'rating' => 5,
                'body' => $this->body(),
            ])
            ->assertNotFound();
    }

    public function test_reader_can_toggle_a_helpful_vote(): void
    {
        $user = User::factory()->create();
        $book = Book::query()->where('slug', 'max-havelaar')->firstOrFail();
        $review = $book->reviews()->firstOrFail();
        $before = $review->helpfuls;

        $this->actingAs($user)
            ->postJson('/api/ulasan/'.$review->id.'/bermanfaat')
            ->assertOk()
            ->assertJsonPath('data.helpful', true)
            ->assertJsonPath('data.helpfuls', $before + 1);

        $reviews = $this->actingAs($user)
            ->getJson('/api/buku/max-havelaar')
            ->assertOk()
            ->json('data.reviews');

        $this->assertTrue(collect($reviews)->firstWhere('id', $review->id)['helpful']);

        $this->actingAs($user)
            ->postJson('/api/ulasan/'.$review->id.'/bermanfaat')
            ->assertOk()
            ->assertJsonPath('data.helpful', false)
            ->assertJsonPath('data.helpfuls', $before);

        $this->assertDatabaseCount('review_helpfuls', 0);
    }

    public function test_guests_cannot_vote_on_reviews(): void
    {
        $review = Review::query()->firstOrFail();

        $this->postJson('/api/ulasan/'.$review->id.'/bermanfaat')->assertUnauthorized();
    }
}

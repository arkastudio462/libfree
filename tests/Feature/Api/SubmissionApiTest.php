<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\Category;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubmissionApiTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->category = Category::query()->create([
            'name' => 'Sastra Klasik',
            'slug' => 'sastra-klasik',
            'description' => 'Sastra pra-kemerdekaan.',
        ]);
    }

    private static function fakeCover(string $name): UploadedFile
    {
        $jpeg = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/wAALCAABAAEBAREA/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAD8AVN//2Q==');

        return UploadedFile::fake()->createWithContent($name, $jpeg);
    }

    private function metadata(array $overrides = []): array
    {
        return array_merge([
            'category_id' => $this->category->id,
            'title' => 'Hikayat Hang Tuah',
            'author_name' => 'Sastra Maritim Melayu',
            'year' => 1700,
            'source_url' => 'https://example.org/hikayat-hang-tuah',
            'license_type' => 'domain-public',
            'notes' => 'Pindai bersih dari arsip umum.',
        ], $overrides);
    }

    private function uploadPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Hikayat Hang Tuah',
            'author_name' => 'Sastra Maritim Melayu',
            'year' => 1700,
            'category' => $this->category->slug,
            'source_url' => 'https://example.org/hikayat-hang-tuah',
            'license_type' => 'domain-public',
            'notes' => 'Pindai bersih dari arsip umum.',
            'manuscript' => UploadedFile::fake()->create('hikayat-hang-tuah.pdf', 120, 'application/pdf'),
            'cover' => self::fakeCover('sampul.jpg'),
        ], $overrides);
    }

    private function submitManuscript(User $user, array $overrides = []): Submission
    {
        $this->actingAs($user)
            ->post('/api/unggahan', $this->uploadPayload($overrides), ['Accept' => 'application/json'])
            ->assertCreated();

        return Submission::query()->latest('id')->firstOrFail();
    }

    public function test_guests_cannot_submit_or_view_submissions(): void
    {
        $this->getJson('/api/unggahan')->assertUnauthorized();
        $this->postJson('/api/unggahan', $this->uploadPayload())->assertUnauthorized();
    }

    public function test_reader_can_submit_a_manuscript_with_files(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/api/unggahan', $this->uploadPayload(), ['Accept' => 'application/json']);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Hikayat Hang Tuah')
            ->assertJsonPath('data.status', Submission::STATUS_PENDING)
            ->assertJsonPath('data.has_manuscript', true)
            ->assertJsonPath('data.has_cover', true)
            ->assertJsonPath('data.category.slug', 'sastra-klasik')
            ->assertJsonPath('data.submitter.email', $user->email);

        $submission = Submission::query()->latest('id')->firstOrFail();

        $this->assertDatabaseHas('submissions', [
            'user_id' => $user->id,
            'category_id' => $this->category->id,
            'title' => 'Hikayat Hang Tuah',
            'status' => Submission::STATUS_PENDING,
        ]);

        Storage::disk('local')->assertExists($submission->manuscript_path);
        Storage::disk('local')->assertExists($submission->cover_path);
    }

    public function test_submission_validates_license_category_source_and_files(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/api/unggahan', [
                'title' => 'Naskah Uji',
                'author_name' => 'Penulis Uji',
                'source_url' => 'bukan-url',
                'license_type' => 'semua-bebas',
                'year' => 999,
                'category' => 'kategori-tidak-ada',
                'manuscript' => UploadedFile::fake()->create('naskah.txt', 20, 'text/plain'),
            ], ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['source_url', 'license_type', 'year', 'category', 'manuscript']);
    }

    public function test_reader_only_sees_their_own_submissions(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $user->submissions()->create($this->metadata(['title' => 'Milik Saya']));
        $other->submissions()->create($this->metadata(['title' => 'Milik Orang']));

        $this->actingAs($user)
            ->getJson('/api/unggahan')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Milik Saya');
    }

    public function test_non_admins_cannot_access_the_moderation_queue(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->getJson('/api/admin/unggahan')->assertForbidden();

        $submission = Submission::query()->create([
            'user_id' => $user->id,
            ...$this->metadata(),
        ]);

        $this->actingAs($user)
            ->patchJson('/api/admin/unggahan/'.$submission->id, ['status' => 'approved'])
            ->assertForbidden();
    }

    public function test_admin_approval_promotes_the_submission_to_an_unpublished_draft(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $reader = User::factory()->create();
        $submission = $this->submitManuscript($reader);

        $this->actingAs($admin)
            ->patchJson('/api/admin/unggahan/'.$submission->id, [
                'status' => 'approved',
                'reviewer_note' => 'Berkas dan lisensi terverifikasi.',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', Submission::STATUS_APPROVED)
            ->assertJsonPath('data.book.is_published', false)
            ->assertJsonPath('data.book.title', 'Hikayat Hang Tuah');

        $submission->refresh();
        $book = Book::query()->findOrFail($submission->book_id);

        $this->assertFalse($book->is_published);
        $this->assertSame('public_domain', $book->license_status);
        $this->assertSame($submission->manuscript_path, $book->manuscript_path);
        $this->assertSame($submission->cover_path, $book->cover_path);
        $this->assertSame('pdf', $book->manuscriptFormat());

        // Draft belum boleh muncul di katalog publik.
        $this->getJson('/api/buku/'.$book->slug)->assertNotFound();
        $this->getJson('/api/buku/'.$book->slug.'/unduh')->assertNotFound();
    }

    public function test_admin_can_reject_and_filter_the_queue(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $reader = User::factory()->create();

        $first = $this->submitManuscript($reader, ['title' => 'Naskah Pertama']);
        $second = Submission::query()->create([
            'user_id' => $reader->id,
            ...$this->metadata(['title' => 'Naskah Kedua']),
        ]);

        $this->actingAs($admin)
            ->getJson('/api/admin/unggahan')
            ->assertOk()
            ->assertJsonPath('meta.pending', 2)
            ->assertJsonCount(2, 'data');

        $this->actingAs($admin)
            ->patchJson('/api/admin/unggahan/'.$first->id, [
                'status' => 'rejected',
                'reviewer_note' => 'Sumber tidak dapat diakses.',
            ])
            ->assertOk()
            ->assertJsonPath('data.status', Submission::STATUS_REJECTED);

        $this->actingAs($admin)
            ->patchJson('/api/admin/unggahan/'.$second->id, [
                'status' => 'approved',
                'reviewer_note' => 'Metadata lengkap.',
            ])
            ->assertOk();

        $this->actingAs($admin)
            ->getJson('/api/admin/unggahan?status=pending')
            ->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.approved', 1)
            ->assertJsonPath('meta.rejected', 1);
    }

    public function test_review_rejects_unknown_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $reader = User::factory()->create();
        $submission = Submission::query()->create([
            'user_id' => $reader->id,
            ...$this->metadata(),
        ]);

        $this->actingAs($admin)
            ->patchJson('/api/admin/unggahan/'.$submission->id, ['status' => 'pending'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');
    }

    public function test_owner_and_admin_can_stream_submission_files(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $reader = User::factory()->create();
        $other = User::factory()->create();
        $submission = $this->submitManuscript($reader);

        $this->actingAs($reader)
            ->get('/api/unggahan/'.$submission->id.'/berkas/naskah')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/api/unggahan/'.$submission->id.'/berkas/sampul')
            ->assertOk();

        $this->actingAs($other)
            ->get('/api/unggahan/'.$submission->id.'/berkas/naskah')
            ->assertForbidden();
    }

    public function test_published_book_can_be_downloaded_and_counts_are_incremented(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $reader = User::factory()->create();
        $submission = $this->submitManuscript($reader);

        $this->actingAs($admin)
            ->patchJson('/api/admin/unggahan/'.$submission->id, ['status' => 'approved'])
            ->assertOk();

        $book = Book::query()->findOrFail($submission->refresh()->book_id);

        $this->actingAs($admin)
            ->patchJson('/api/admin/konten/'.$book->id, ['is_published' => true])
            ->assertOk();

        $this->get('/api/buku/'.$book->slug.'/unduh')
            ->assertOk()
            ->assertDownload($submission->manuscript_name);

        $this->assertSame(1, $book->refresh()->download_count);
    }
}

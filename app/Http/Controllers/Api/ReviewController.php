<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Review;
use App\Models\ReviewHelpful;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    /**
     * Publish (or update) the signed-in reader's review for a published book.
     */
    public function store(Request $request, string $slug): JsonResponse
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        /** @var User $user */
        $user = $request->user();

        $existing = Review::query()
            ->where('book_id', $book->id)
            ->where('user_id', $user->id)
            ->first();

        $isNew = $existing === null;

        $review = DB::transaction(function () use ($book, $user, $data, $existing, $isNew) {
            $previousRating = $isNew ? null : (float) $existing->rating;

            if ($isNew) {
                $review = new Review([
                    'book_id' => $book->id,
                    'user_id' => $user->id,
                    ...$this->reviewerIdentity($user),
                    'meta' => $this->reviewerMeta(),
                    'rating' => (float) $data['rating'],
                    'body' => $data['body'],
                    'helpfuls' => 0,
                ]);
            } else {
                $review = $existing;
                $review->fill([
                    'rating' => (float) $data['rating'],
                    'body' => $data['body'],
                    'meta' => $this->reviewerMeta(),
                ]);
            }

            $review->save();

            $this->syncAggregate($book, (float) $data['rating'], $previousRating, $isNew);

            return $review;
        });

        $book->refresh();

        return response()->json([
            'data' => $review->toPublicArray($user->id),
            'meta' => [
                'rating' => $book->rating,
                'reviews_count' => $book->reviews_count,
            ],
            'message' => $isNew
                ? 'Ulasanmu sudah terbit di halaman buku ini.'
                : 'Ulasanmu sudah diperbarui.',
        ], $isNew ? 201 : 200);
    }

    /**
     * Toggle the signed-in reader's "helpful" vote on a review.
     */
    public function toggleHelpful(Request $request, Review $review): JsonResponse
    {
        abort_unless($review->book()->published()->exists(), 404);

        $user = $request->user();

        $helpful = DB::transaction(function () use ($review, $user) {
            $existing = ReviewHelpful::query()
                ->where('review_id', $review->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existing) {
                $existing->delete();

                if ($review->helpfuls > 0) {
                    $review->decrement('helpfuls');
                }

                return false;
            }

            ReviewHelpful::query()->create([
                'review_id' => $review->id,
                'user_id' => $user->id,
            ]);

            $review->increment('helpfuls');

            return true;
        });

        return response()->json([
            'data' => [
                'helpful' => $helpful,
                'helpfuls' => $review->refresh()->helpfuls,
            ],
        ]);
    }

    /**
     * Keep the denormalised rating/review count on the book in sync.
     */
    private function syncAggregate(Book $book, float $rating, ?float $previousRating, bool $isNew): void
    {
        $count = max((int) $book->reviews_count, 0);

        if ($isNew) {
            $newCount = $count + 1;
            $newRating = $count > 0
                ? (($book->rating * $count) + $rating) / $newCount
                : $rating;
        } else {
            $newCount = max($count, 1);
            $newRating = $previousRating === null
                ? $rating
                : (($book->rating * $newCount) - $previousRating + $rating) / $newCount;
        }

        $book->forceFill([
            'reviews_count' => $newCount,
            'rating' => round($newRating, 1),
        ])->save();
    }

    /**
     * Display identity derived from the reader's account.
     */
    private function reviewerIdentity(User $user): array
    {
        $name = $user->name;
        $words = preg_split('/\s+/u', trim($name)) ?: [];

        $initials = collect($words)
            ->filter()
            ->take(2)
            ->map(fn (string $word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');

        if ($initials === '') {
            $initials = Str::upper(Str::substr($name, 0, 2));
        }

        $palette = [
            'bg-brand-coral',
            'bg-brand-blue',
            'bg-brand-green',
            'bg-brand-yellow',
            'bg-brand-blue-light',
            'bg-brand-coral-light',
        ];

        return [
            'name' => $name,
            'initials' => $initials,
            'avatar_class' => $palette[abs(crc32((string) $user->id)) % count($palette)],
            'badge_text' => 'Pembaca',
            'badge_class' => 'bg-brand-blue-light text-brand-dark',
        ];
    }

    private function reviewerMeta(): string
    {
        return 'Ulasan pembaca • '.now()->locale('id')->isoFormat('D MMMM YYYY');
    }
}

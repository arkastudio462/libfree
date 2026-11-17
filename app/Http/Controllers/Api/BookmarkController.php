<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookmarkController extends Controller
{
    /**
     * List the authenticated reader's saved books (Rak Baca).
     */
    public function index(Request $request): JsonResponse
    {
        $books = $request->user()
            ->bookmarkedBooks()
            ->where('books.is_published', true)
            ->with(['author:id,name,slug,short_name,initials,avatar_class,line,badge,bio,subtitle,photo_url,favorite_rank', 'category:id,name,slug,icon'])
            ->orderByDesc('bookmarks.created_at')
            ->paginate(min(max((int) $request->query('per_page', 12), 1), 48));

        return response()->json([
            'data' => $books->getCollection()
                ->map(fn (Book $book) => $book->toCatalogArray(true))
                ->values(),
            'meta' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
            ],
        ]);
    }

    /**
     * Bookmark state for one book.
     */
    public function status(Request $request, string $slug): JsonResponse
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();
        $bookmarked = $request->user()->bookmarks()->where('book_id', $book->id)->exists();

        return response()->json([
            'data' => [
                'bookmarked' => $bookmarked,
                'bookmark_count' => $book->bookmark_count,
            ],
        ]);
    }

    /**
     * Save a book to the reader's shelf.
     */
    public function store(Request $request, string $slug): JsonResponse
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();

        $bookmark = DB::transaction(function () use ($request, $book) {
            $bookmark = $request->user()->bookmarks()->firstOrCreate(['book_id' => $book->id]);

            if ($bookmark->wasRecentlyCreated) {
                $book->increment('bookmark_count');
            }

            return $bookmark;
        });

        return response()->json([
            'data' => [
                'bookmarked' => true,
                'bookmark_count' => $book->fresh()->bookmark_count,
            ],
        ], $bookmark->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Remove a book from the reader's shelf.
     */
    public function destroy(Request $request, string $slug): JsonResponse
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();

        DB::transaction(function () use ($request, $book) {
            $deleted = $request->user()->bookmarks()->where('book_id', $book->id)->delete();

            if ($deleted) {
                $book->bookmark_count = max(0, $book->bookmark_count - 1);
                $book->save();
            }
        });

        return response()->json([
            'data' => [
                'bookmarked' => false,
                'bookmark_count' => $book->bookmark_count,
            ],
        ]);
    }
}

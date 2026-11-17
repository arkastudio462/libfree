<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReaderController extends Controller
{
    /**
     * Return the chapter payload used by the in-browser reader.
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $book = Book::query()
            ->published()
            ->with([
                'author:id,name,slug,short_name,initials,avatar_class',
                'category:id,name,slug,icon',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $chapters = collect($book->reader_content ?? [])
            ->values()
            ->map(fn (array $chapter, int $index) => [
                'number' => $index + 1,
                'title' => $chapter['title'] ?? 'Bab '.($index + 1),
                'paragraphs' => array_values($chapter['paragraphs'] ?? []),
            ]);

        // Only formats the frontend reader can actually render are exposed.
        $format = $book->manuscriptFormat();
        $hasManuscript = filled($book->manuscript_path)
            && in_array($format, ['pdf', 'epub'], true)
            && Storage::disk('local')->exists($book->manuscript_path);

        $isBookmarked = $request->user()
            ? Bookmark::query()->where('user_id', $request->user()->id)->where('book_id', $book->id)->exists()
            : false;

        return response()->json([
            'data' => [
                'book' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'slug' => $book->slug,
                    'pages' => $book->pages,
                    'synopsis' => $book->synopsis,
                    'quote' => $book->quote,
                    'quote_source' => $book->quote_source,
                    'reader_available' => $hasManuscript || $chapters->isNotEmpty(),
                    'has_manuscript' => $hasManuscript,
                    'manuscript' => $hasManuscript ? [
                        'format' => $format,
                        'url' => '/api/buku/'.$book->slug.'/berkas',
                        'name' => $book->manuscript_name,
                        'size' => $book->manuscript_size,
                        'mime' => $book->manuscript_mime,
                    ] : null,
                    'is_bookmarked' => $isBookmarked,
                    'author' => $book->author ? [
                        'id' => $book->author->id,
                        'name' => $book->author->name,
                        'slug' => $book->author->slug,
                        'initials' => $book->author->initials,
                        'avatar_class' => $book->author->avatar_class,
                    ] : null,
                    'category' => $book->category ? [
                        'id' => $book->category->id,
                        'name' => $book->category->name,
                        'slug' => $book->category->slug,
                        'icon' => $book->category->icon,
                    ] : null,
                ],
                'chapters' => $chapters,
                'total_chapters' => $chapters->count(),
            ],
        ]);
    }
}

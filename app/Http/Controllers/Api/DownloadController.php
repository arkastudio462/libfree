<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    /**
     * Stream the manuscript inline so the in-browser reader can page through it.
     *
     * `response()->file()` produces a BinaryFileResponse, which honours HTTP
     * Range requests — required for PDF.js to jump between pages without
     * downloading the whole file first.
     */
    public function read(string $slug): BinaryFileResponse
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();

        abort_unless(
            $book->manuscript_path && Storage::disk('local')->exists($book->manuscript_path),
            404
        );

        $filename = $book->manuscript_name
            ?: str($book->title)->slug().'.'.($book->manuscriptFormat() ?: 'pdf');

        return response()
            ->file(Storage::disk('local')->path($book->manuscript_path), [
                'Content-Type' => $book->manuscript_mime ?: 'application/octet-stream',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
            ])
            ->setContentDisposition('inline', $filename);
    }

    /**
     * Stream the manuscript of a published book.
     */
    public function manuscript(string $slug): StreamedResponse
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();

        abort_unless(
            $book->manuscript_path && Storage::disk('local')->exists($book->manuscript_path),
            404
        );

        $book->increment('download_count');

        $extension = $book->manuscriptFormat() ?: 'pdf';
        $filename = $book->manuscript_name
            ?: str($book->title)->slug().'.'.$extension;

        return Storage::disk('local')->download(
            $book->manuscript_path,
            $filename,
            ['Content-Type' => $book->manuscript_mime ?: 'application/octet-stream']
        );
    }

    /**
     * Render the cover image of a published book inline.
     */
    public function cover(string $slug): StreamedResponse
    {
        $book = Book::query()->published()->where('slug', $slug)->firstOrFail();

        abort_unless(
            $book->cover_path && Storage::disk('local')->exists($book->cover_path),
            404
        );

        return Storage::disk('local')->response(
            $book->cover_path,
            'sampul-'.$book->slug,
            ['Content-Type' => $book->cover_mime ?: 'image/jpeg']
        );
    }
}

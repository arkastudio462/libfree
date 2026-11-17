<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * List authors with book counts; `favorit=1` returns the homepage
     * "Penulis Terfavorit" ranking.
     *
     * Counts and rankings only include books that passed the license audit.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Author::query()
            ->withCount(['books' => fn (Builder $q) => $q->published()]);

        if ($request->query('favorit')) {
            $query->whereNotNull('favorite_rank')
                ->whereHas('books', fn (Builder $q) => $q->published())
                ->orderBy('favorite_rank');
        } else {
            $query->orderBy('name');
        }

        return response()->json([
            'data' => $query->get(),
        ]);
    }
}

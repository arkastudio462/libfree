<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * List categories with real book counts (sidebar + quick filter pills).
     *
     * Counts only include books that passed the license audit.
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->withCount(['books' => fn (Builder $query) => $query->published()])
            ->orderBy('id')
            ->get(['id', 'name', 'slug', 'icon', 'description']);

        return response()->json([
            'data' => $categories,
        ]);
    }
}

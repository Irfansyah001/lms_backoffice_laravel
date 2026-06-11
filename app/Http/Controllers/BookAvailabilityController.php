<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookAvailabilityController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');

        $books = Book::with('category')
            ->when($search, function ($query, string $search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        return view('book-availability.index', compact('books', 'search'));
    }
}

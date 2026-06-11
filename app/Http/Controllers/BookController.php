<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Rack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');

        $books = Book::with(['category', 'rack'])
            ->when($search, function ($query, string $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('books.index', compact('books', 'search'));
    }

    public function create(): View
    {
        return view('books.create', [
            'categories' => Category::orderBy('name')->get(),
            'racks' => Rack::orderBy('name')->get(),
        ]);
    }

    public function show(Book $book): View
    {
        $book->load(['category', 'rack', 'createdBy', 'updatedBy', 'borrowings.member']);

        return view('books.show', compact('book'));
    }

    public function store(Request $request): RedirectResponse
    {
        Book::create([
            ...$this->validatedData($request),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book): View
    {
        return view('books.edit', [
            'book' => $book,
            'categories' => Category::orderBy('name')->get(),
            'racks' => Rack::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $book->update([
            ...$this->validatedData($request),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->borrowings()->exists()) {
            return back()->withErrors(['book' => 'Buku tidak bisa dihapus karena sudah memiliki riwayat peminjaman.']);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'rack_id' => ['nullable', 'exists:racks,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publication_year' => ['nullable', 'integer', 'min:1000', 'max:'.(date('Y') + 1)],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $data['status'] = $data['stock'] > 0 ? 'tersedia' : 'tidak_tersedia';

        return $data;
    }
}

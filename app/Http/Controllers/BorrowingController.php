<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');
        $status = $request->input('status');

        $borrowings = Borrowing::with(['member', 'book'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query, string $search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->whereHas('member', function ($memberQuery) use ($search) {
                        $memberQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('member_code', 'like', "%{$search}%");
                    })->orWhereHas('book', function ($bookQuery) use ($search) {
                        $bookQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('author', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('borrowings.index', compact('borrowings', 'search', 'status'));
    }

    public function create(): View
    {
        return view('borrowings.create', $this->formData());
    }

    public function show(Borrowing $borrowing): View
    {
        $borrowing->load(['member', 'book.category', 'createdBy', 'updatedBy']);

        return view('borrowings.show', compact('borrowing'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        DB::transaction(function () use ($data) {
            $member = Member::findOrFail($data['member_id']);
            $book = Book::lockForUpdate()->findOrFail($data['book_id']);

            if ($member->status !== 'aktif') {
                throw ValidationException::withMessages([
                    'member_id' => 'Anggota harus berstatus aktif.',
                ]);
            }

            if (! $book->isAvailable()) {
                throw ValidationException::withMessages([
                    'book_id' => 'Stok buku tidak tersedia untuk dipinjam.',
                ]);
            }

            $activeBorrowings = Borrowing::where('member_id', $member->id)
                ->where('status', 'dipinjam')
                ->count();

            if ($activeBorrowings >= 3) {
                throw ValidationException::withMessages([
                    'member_id' => 'Anggota ini sudah mencapai batas maksimal 3 peminjaman aktif.',
                ]);
            }

            $alreadyBorrowed = Borrowing::where('member_id', $member->id)
                ->where('book_id', $book->id)
                ->where('status', 'dipinjam')
                ->exists();

            if ($alreadyBorrowed) {
                throw ValidationException::withMessages([
                    'book_id' => 'Anggota ini masih memiliki peminjaman aktif untuk buku yang sama.',
                ]);
            }

            Borrowing::create([
                ...$data,
                'status' => 'dipinjam',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $book->decrement('stock');
            $book->refresh()->syncAvailabilityStatus();
        });

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function edit(Borrowing $borrowing): View|RedirectResponse
    {
        if ($borrowing->isReturned()) {
            return redirect()->route('borrowings.index')->withErrors([
                'borrowing' => 'Peminjaman yang sudah dikembalikan tidak bisa diedit.',
            ]);
        }

        return view('borrowings.edit', [
            'borrowing' => $borrowing,
            ...$this->formData($borrowing),
        ]);
    }

    public function update(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->isReturned()) {
            return redirect()->route('borrowings.index')->withErrors([
                'borrowing' => 'Peminjaman yang sudah dikembalikan tidak bisa diedit.',
            ]);
        }

        $data = $this->validatedData($request);

        DB::transaction(function () use ($borrowing, $data) {
            $member = Member::findOrFail($data['member_id']);

            if ($member->status !== 'aktif') {
                throw ValidationException::withMessages([
                    'member_id' => 'Anggota harus berstatus aktif.',
                ]);
            }

            if ((int) $borrowing->member_id !== (int) $member->id) {
                $activeBorrowings = Borrowing::where('member_id', $member->id)
                    ->where('status', 'dipinjam')
                    ->count();

                if ($activeBorrowings >= 3) {
                    throw ValidationException::withMessages([
                        'member_id' => 'Anggota ini sudah mencapai batas maksimal 3 peminjaman aktif.',
                    ]);
                }
            }

            $alreadyBorrowed = Borrowing::where('member_id', $member->id)
                ->where('book_id', $data['book_id'])
                ->where('status', 'dipinjam')
                ->whereKeyNot($borrowing->id)
                ->exists();

            if ($alreadyBorrowed) {
                throw ValidationException::withMessages([
                    'book_id' => 'Anggota ini masih memiliki peminjaman aktif untuk buku yang sama.',
                ]);
            }

            if ((int) $borrowing->book_id !== (int) $data['book_id']) {
                $oldBook = Book::lockForUpdate()->findOrFail($borrowing->book_id);
                $newBook = Book::lockForUpdate()->findOrFail($data['book_id']);

                if (! $newBook->isAvailable()) {
                    throw ValidationException::withMessages([
                        'book_id' => 'Stok buku pengganti tidak tersedia.',
                    ]);
                }

                $oldBook->increment('stock');
                $oldBook->refresh()->syncAvailabilityStatus();

                $newBook->decrement('stock');
                $newBook->refresh()->syncAvailabilityStatus();
            }

            $borrowing->update([
                ...$data,
                'updated_by' => auth()->id(),
            ]);
        });

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(Borrowing $borrowing): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($borrowing->isClosed()) {
            return back()->withErrors(['borrowing' => 'Transaksi yang sudah selesai tidak bisa dibatalkan.']);
        }

        DB::transaction(function () use ($borrowing) {
            $book = Book::lockForUpdate()->findOrFail($borrowing->book_id);
            $book->increment('stock');
            $book->refresh()->syncAvailabilityStatus();

            $borrowing->update([
                'status' => 'dibatalkan',
                'updated_by' => auth()->id(),
            ]);
        });

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'book_id' => ['required', 'exists:books,id'],
            'borrowed_at' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after:borrowed_at'],
        ]);
    }

    private function formData(?Borrowing $borrowing = null): array
    {
        $books = Book::query()
            ->where(function ($query) use ($borrowing) {
                $query->where(function ($availableBookQuery) {
                    $availableBookQuery->where('status', 'tersedia')
                        ->where('stock', '>', 0);
                });

                if ($borrowing) {
                    $query->orWhere('id', $borrowing->book_id);
                }
            })
            ->orderBy('title')
            ->get();

        return [
            'members' => Member::where('status', 'aktif')->orderBy('name')->get(),
            'books' => $books,
        ];
    }
}

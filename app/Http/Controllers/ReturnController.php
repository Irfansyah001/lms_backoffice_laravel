<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');

        $borrowings = Borrowing::with(['member', 'book'])
            ->when($search, function ($query, string $search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->whereHas('member', fn ($memberQuery) => $memberQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('book', fn ($bookQuery) => $bookQuery->where('title', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('returns.index', compact('borrowings', 'search'));
    }

    public function edit(Borrowing $borrowing): View|RedirectResponse
    {
        if ($borrowing->isClosed()) {
            return redirect()->route('returns.index')->withErrors([
                'return' => 'Transaksi ini sudah selesai atau dibatalkan.',
            ]);
        }

        return view('returns.edit', compact('borrowing'));
    }

    public function update(Request $request, Borrowing $borrowing): RedirectResponse
    {
        if ($borrowing->isClosed()) {
            return redirect()->route('returns.index')->withErrors([
                'return' => 'Transaksi ini sudah selesai atau dibatalkan.',
            ]);
        }

        $data = $request->validate([
            'returned_at' => [
                'required',
                'date',
                'after_or_equal:'.$borrowing->borrowed_at->toDateString(),
                'before_or_equal:today',
            ],
        ]);

        DB::transaction(function () use ($borrowing, $data) {
            $dueDate = Carbon::parse($borrowing->due_date);
            $returnedAt = Carbon::parse($data['returned_at']);
            $lateDays = $returnedAt->greaterThan($dueDate) ? (int) $dueDate->diffInDays($returnedAt) : 0;

            $borrowing->update([
                'returned_at' => $returnedAt->toDateString(),
                'status' => $lateDays > 0 ? 'terlambat' : 'dikembalikan',
                'updated_by' => auth()->id(),
            ]);

            $book = Book::lockForUpdate()->findOrFail($borrowing->book_id);
            $book->increment('stock');
            $book->refresh()->syncAvailabilityStatus();

        });

        return redirect()->route('returns.index')->with('success', 'Pengembalian berhasil dicatat.');
    }
}

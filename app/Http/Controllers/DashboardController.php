<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $overdueBooks = Borrowing::where('status', 'dipinjam')
            ->whereDate('due_date', '<', today())
            ->count();

        $statCards = auth()->user()->isAdmin()
            ? [
                ['label' => 'Total Buku', 'value' => Book::count()],
                ['label' => 'Total Anggota', 'value' => Member::count()],
                ['label' => 'Peminjaman Aktif', 'value' => Borrowing::where('status', 'dipinjam')->count()],
                ['label' => 'Buku Terlambat', 'value' => $overdueBooks],
            ]
            : [
                ['label' => 'Anggota Aktif', 'value' => Member::where('status', 'aktif')->count()],
                ['label' => 'Peminjaman Hari Ini', 'value' => Borrowing::whereDate('borrowed_at', today())->count()],
                ['label' => 'Pengembalian Hari Ini', 'value' => Borrowing::whereDate('returned_at', today())->count()],
                ['label' => 'Buku Terlambat Aktif', 'value' => $overdueBooks],
            ];

        $recentBorrowings = Borrowing::with(['member', 'book'])
            ->latest()
            ->take(5)
            ->get();

        $lowStockBooks = Book::with('category')
            ->where('stock', '<=', 2)
            ->orderBy('stock')
            ->take(5)
            ->get();

        $overdueBorrowings = Borrowing::with(['member', 'book'])
            ->where('status', 'dipinjam')
            ->whereDate('due_date', '<', today())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        return view('dashboard', compact('statCards', 'recentBorrowings', 'lowStockBooks', 'overdueBorrowings'));
    }
}

@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan buku, anggota, peminjaman aktif, dan keterlambatan.')

@section('content')
    <section class="grid stats">
        @foreach ($statCards as $stat)
            <div class="card">
                <p class="stat-label">{{ $stat['label'] }}</p>
                <p class="stat-value">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </section>

    <section class="grid two">
        <div class="table-wrap">
            <div class="section-head">
                <h2>Peminjaman Terbaru</h2>
                <a class="btn secondary small" href="{{ route('borrowings.index') }}">Lihat semua</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentBorrowings as $borrowing)
                        <tr>
                            <td>{{ $borrowing->member->name }}</td>
                            <td>{{ $borrowing->book->title }}</td>
                            <td><span class="badge {{ $borrowing->status === 'terlambat' ? 'danger' : 'success' }}">{{ ucfirst($borrowing->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Belum ada peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-wrap">
            <div class="section-head">
                @if (auth()->user()->isAdmin())
                    <h2>Stok Menipis</h2>
                    <a class="btn secondary small" href="{{ route('books.index') }}">Kelola buku</a>
                @else
                    <h2>Buku Terlambat Aktif</h2>
                @endif
            </div>
            <table>
                <thead>
                    @if (auth()->user()->isAdmin())
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                        </tr>
                    @else
                        <tr>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Jatuh Tempo</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @if (auth()->user()->isAdmin())
                        @forelse ($lowStockBooks as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->category->name }}</td>
                                <td><span class="badge warning">{{ $book->stock }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3">Stok aman.</td></tr>
                        @endforelse
                    @else
                        @forelse ($overdueBorrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->member->name }}</td>
                                <td>{{ $borrowing->book->title }}</td>
                                <td><span class="badge danger">{{ $borrowing->due_date->format('d/m/Y') }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3">Tidak ada buku terlambat aktif.</td></tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Peminjaman Buku')
@section('subtitle', 'Catat peminjaman buku dan pantau status transaksi.')

@section('content')
    <div class="toolbar">
        <form class="search-form" method="GET" action="{{ route('borrowings.index') }}">
            <div class="field">
                <label for="q">Cari transaksi</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Anggota atau buku">
            </div>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Semua</option>
                    <option value="dipinjam" @selected($status === 'dipinjam')>Dipinjam</option>
                    <option value="dikembalikan" @selected($status === 'dikembalikan')>Dikembalikan</option>
                    <option value="terlambat" @selected($status === 'terlambat')>Terlambat</option>
                    <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
                </select>
            </div>
            <button class="btn secondary" type="submit">Filter</button>
        </form>

        <a class="btn" href="{{ route('borrowings.create') }}">Tambah Peminjaman</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($borrowings as $borrowing)
                    @php
                        $isActiveLate = $borrowing->status === 'dipinjam' && $borrowing->due_date->lt(today());
                        $badgeClass = $borrowing->status === 'terlambat' || $isActiveLate ? 'danger' : ($borrowing->status === 'dipinjam' ? 'warning' : ($borrowing->status === 'dibatalkan' ? 'danger' : 'success'));
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $borrowing->member->name }}</strong><br>
                            <span class="muted">{{ $borrowing->member->member_code }}</span>
                        </td>
                        <td>{{ $borrowing->book->title }}</td>
                        <td>
                            Pinjam: {{ $borrowing->borrowed_at->format('d/m/Y') }}<br>
                            Jatuh tempo: {{ $borrowing->due_date->format('d/m/Y') }}<br>
                            Kembali: {{ $borrowing->returned_at?->format('d/m/Y') ?: '-' }}
                        </td>
                        <td><span class="badge {{ $badgeClass }}">{{ $isActiveLate ? 'Terlambat' : ucfirst($borrowing->status) }}</span></td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary small" href="{{ route('borrowings.show', $borrowing) }}">Detail</a>
                                @if (! $borrowing->isClosed())
                                    <a class="btn secondary small" href="{{ route('borrowings.edit', $borrowing) }}">Edit</a>
                                    <a class="btn warning small" href="{{ route('returns.edit', $borrowing) }}">Kembalikan</a>
                                @endif
                                @if (auth()->user()->isAdmin() && ! $borrowing->isClosed())
                                    <form method="POST" action="{{ route('borrowings.destroy', $borrowing) }}" onsubmit="return confirm('Batalkan transaksi ini? Stok buku akan dikembalikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn danger small" type="submit">Batalkan</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Data peminjaman belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $borrowings])
@endsection

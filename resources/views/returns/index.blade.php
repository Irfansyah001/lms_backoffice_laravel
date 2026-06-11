@extends('layouts.app')

@section('title', 'Pengembalian Buku')
@section('subtitle', 'Catat tanggal pengembalian dan status keterlambatan otomatis.')

@section('content')
    <div class="toolbar">
        <form class="search-form" method="GET" action="{{ route('returns.index') }}">
            <div class="field">
                <label for="q">Cari pengembalian</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Anggota atau buku">
            </div>
            <button class="btn secondary" type="submit">Cari</button>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Jatuh Tempo</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($borrowings as $borrowing)
                    <tr>
                        <td>{{ $borrowing->member->name }}</td>
                        <td>{{ $borrowing->book->title }}</td>
                        <td>{{ $borrowing->due_date->format('d/m/Y') }}</td>
                        <td>{{ $borrowing->returned_at?->format('d/m/Y') ?: '-' }}</td>
                        <td><span class="badge {{ $borrowing->status === 'terlambat' || $borrowing->status === 'dibatalkan' ? 'danger' : ($borrowing->status === 'dipinjam' ? 'warning' : 'success') }}">{{ ucfirst($borrowing->status) }}</span></td>
                        <td>
                            @if (! $borrowing->isClosed())
                                <a class="btn warning small" href="{{ route('returns.edit', $borrowing) }}">Catat Kembali</a>
                            @else
                                <span class="muted">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Data pengembalian belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $borrowings])
@endsection

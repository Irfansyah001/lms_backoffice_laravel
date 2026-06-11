@extends('layouts.app')

@section('title', 'Detail Buku')
@section('subtitle', 'Lihat data buku, status stok, audit perubahan, dan riwayat peminjaman.')

@section('content')
    <section class="form-card">
        <dl class="detail-list">
            <dt>Judul</dt>
            <dd>{{ $book->title }}</dd>

            <dt>Penulis</dt>
            <dd>{{ $book->author }}</dd>

            <dt>Penerbit</dt>
            <dd>{{ $book->publisher ?: '-' }}</dd>

            <dt>Tahun Terbit</dt>
            <dd>{{ $book->publication_year ?: '-' }}</dd>

            <dt>Kategori</dt>
            <dd>{{ $book->category->name }}</dd>

            <dt>Rak</dt>
            <dd>{{ $book->rack?->name ?: '-' }}</dd>

            <dt>Stok</dt>
            <dd>{{ $book->stock }}</dd>

            <dt>Status</dt>
            <dd><span class="badge {{ $book->isAvailable() ? 'success' : 'danger' }}">{{ $book->isAvailable() ? 'Tersedia' : 'Tidak tersedia' }}</span></dd>

            <dt>Dibuat oleh</dt>
            <dd>{{ $book->createdBy?->name ?: '-' }}</dd>

            <dt>Terakhir diubah oleh</dt>
            <dd>{{ $book->updatedBy?->name ?: '-' }}</dd>
        </dl>

        <div class="actions" style="margin-top:18px;">
            <a class="btn secondary" href="{{ route('books.index') }}">Kembali</a>
            <a class="btn" href="{{ route('books.edit', $book) }}">Edit</a>
        </div>
    </section>

    <div class="table-wrap">
        <div class="section-head">
            <h2>Riwayat Peminjaman</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($book->borrowings as $borrowing)
                    <tr>
                        <td>{{ $borrowing->member->name }}</td>
                        <td>{{ $borrowing->borrowed_at->format('d/m/Y') }}</td>
                        <td>{{ $borrowing->due_date->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($borrowing->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Buku belum pernah dipinjam.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

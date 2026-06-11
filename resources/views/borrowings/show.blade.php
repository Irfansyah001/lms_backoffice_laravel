@extends('layouts.app')

@section('title', 'Detail Peminjaman')
@section('subtitle', 'Lihat detail transaksi peminjaman dan audit pencatatan.')

@section('content')
    <section class="form-card">
        <dl class="detail-list">
            <dt>Anggota</dt>
            <dd>{{ $borrowing->member->name }} ({{ $borrowing->member->member_code }})</dd>

            <dt>Buku</dt>
            <dd>{{ $borrowing->book->title }}</dd>

            <dt>Kategori Buku</dt>
            <dd>{{ $borrowing->book->category->name }}</dd>

            <dt>Tanggal Pinjam</dt>
            <dd>{{ $borrowing->borrowed_at->format('d/m/Y') }}</dd>

            <dt>Jatuh Tempo</dt>
            <dd>{{ $borrowing->due_date->format('d/m/Y') }}</dd>

            <dt>Tanggal Kembali</dt>
            <dd>{{ $borrowing->returned_at?->format('d/m/Y') ?: '-' }}</dd>

            <dt>Status</dt>
            <dd><span class="badge {{ $borrowing->status === 'dipinjam' ? 'warning' : ($borrowing->status === 'dikembalikan' ? 'success' : 'danger') }}">{{ ucfirst($borrowing->status) }}</span></dd>

            <dt>Dibuat oleh</dt>
            <dd>{{ $borrowing->createdBy?->name ?: '-' }}</dd>

            <dt>Terakhir diubah oleh</dt>
            <dd>{{ $borrowing->updatedBy?->name ?: '-' }}</dd>
        </dl>

        <div class="actions" style="margin-top:18px;">
            <a class="btn secondary" href="{{ route('borrowings.index') }}">Kembali</a>
            @if (! $borrowing->isClosed())
                <a class="btn" href="{{ route('borrowings.edit', $borrowing) }}">Edit</a>
                <a class="btn warning" href="{{ route('returns.edit', $borrowing) }}">Kembalikan</a>
            @endif
        </div>
    </section>
@endsection

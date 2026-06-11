@extends('layouts.app')

@section('title', 'Catat Pengembalian')
@section('subtitle', 'Sistem akan menambah stok dan menandai status terlambat jika melewati jatuh tempo.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('returns.update', $borrowing) }}">
        @csrf
        @method('PUT')

        <div class="grid">
            <div class="summary-box">
                <p><strong>Anggota:</strong> {{ $borrowing->member->name }} ({{ $borrowing->member->member_code }})</p>
                <p><strong>Buku:</strong> {{ $borrowing->book->title }}</p>
                <p><strong>Tanggal Pinjam:</strong> {{ $borrowing->borrowed_at->format('d/m/Y') }}</p>
                <p><strong>Jatuh Tempo:</strong> {{ $borrowing->due_date->format('d/m/Y') }}</p>
            </div>

            <div class="field">
                <label for="returned_at">Tanggal Pengembalian</label>
                <input id="returned_at" type="date" name="returned_at" value="{{ old('returned_at', now()->toDateString()) }}" required>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan Pengembalian</button>
            <a class="btn secondary" href="{{ route('returns.index') }}">Batal</a>
        </div>
    </form>
@endsection

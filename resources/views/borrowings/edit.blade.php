@extends('layouts.app')

@section('title', 'Edit Peminjaman')
@section('subtitle', 'Perbarui transaksi yang masih aktif.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('borrowings.update', $borrowing) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="member_id">Anggota</label>
                <select id="member_id" name="member_id" required>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" @selected(old('member_id', $borrowing->member_id) == $member->id)>{{ $member->member_code }} - {{ $member->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="book_id">Buku</label>
                <select id="book_id" name="book_id" required>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" @selected(old('book_id', $borrowing->book_id) == $book->id)>{{ $book->title }} (stok: {{ $book->stock }})</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="borrowed_at">Tanggal Pinjam</label>
                <input id="borrowed_at" type="date" name="borrowed_at" value="{{ old('borrowed_at', $borrowing->borrowed_at->toDateString()) }}" required>
            </div>

            <div class="field">
                <label for="due_date">Tanggal Jatuh Tempo</label>
                <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $borrowing->due_date->toDateString()) }}" required>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan Perubahan</button>
            <a class="btn secondary" href="{{ route('borrowings.index') }}">Batal</a>
        </div>
    </form>
@endsection

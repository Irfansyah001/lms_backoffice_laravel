@extends('layouts.app')

@section('title', 'Tambah Peminjaman')
@section('subtitle', 'Sistem akan mengecek stok buku sebelum transaksi disimpan.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('borrowings.store') }}">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="member_id">Anggota</label>
                <select id="member_id" name="member_id" required>
                    <option value="">Pilih anggota aktif</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>{{ $member->member_code }} - {{ $member->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="book_id">Buku</label>
                <select id="book_id" name="book_id" required>
                    <option value="">Pilih buku</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>{{ $book->title }} (stok: {{ $book->stock }})</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="borrowed_at">Tanggal Pinjam</label>
                <input id="borrowed_at" type="date" name="borrowed_at" value="{{ old('borrowed_at', now()->toDateString()) }}" required>
            </div>

            <div class="field">
                <label for="due_date">Tanggal Jatuh Tempo</label>
                <input id="due_date" type="date" name="due_date" value="{{ old('due_date', now()->addDays(7)->toDateString()) }}" required>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn secondary" href="{{ route('borrowings.index') }}">Batal</a>
        </div>
    </form>
@endsection

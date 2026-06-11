@extends('layouts.app')

@section('title', 'Tambah Buku')
@section('subtitle', 'Masukkan data koleksi buku baru.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('books.store') }}">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="title">Judul Buku</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="field">
                <label for="author">Penulis</label>
                <input id="author" type="text" name="author" value="{{ old('author') }}" required>
            </div>

            <div class="field">
                <label for="publisher">Penerbit</label>
                <input id="publisher" type="text" name="publisher" value="{{ old('publisher') }}">
            </div>

            <div class="field">
                <label for="publication_year">Tahun Terbit</label>
                <input id="publication_year" type="number" name="publication_year" value="{{ old('publication_year') }}" min="1000" max="{{ date('Y') + 1 }}">
            </div>

            <div class="field">
                <label for="category_id">Kategori</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="stock">Stok</label>
                <input id="stock" type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required>
            </div>

            <div class="field">
                <label for="shelf_location">Lokasi Rak</label>
                <input id="shelf_location" type="text" name="shelf_location" value="{{ old('shelf_location') }}">
            </div>
        </div>

        <div class="summary-box" style="margin-top:16px;">
            <p>Status buku otomatis mengikuti stok: stok lebih dari 0 berarti tersedia, stok 0 berarti tidak tersedia.</p>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn secondary" href="{{ route('books.index') }}">Batal</a>
        </div>
    </form>
@endsection

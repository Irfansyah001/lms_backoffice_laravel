@extends('layouts.app')

@section('title', 'Edit Buku')
@section('subtitle', 'Perbarui data koleksi buku.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('books.update', $book) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="title">Judul Buku</label>
                <input id="title" type="text" name="title" value="{{ old('title', $book->title) }}" required>
            </div>

            <div class="field">
                <label for="author">Penulis</label>
                <input id="author" type="text" name="author" value="{{ old('author', $book->author) }}" required>
            </div>

            <div class="field">
                <label for="publisher">Penerbit</label>
                <input id="publisher" type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}">
            </div>

            <div class="field">
                <label for="publication_year">Tahun Terbit</label>
                <input id="publication_year" type="number" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}" min="1000" max="{{ date('Y') + 1 }}">
            </div>

            <div class="field">
                <label for="category_id">Kategori</label>
                <select id="category_id" name="category_id" required data-combobox data-placeholder="Ketik nama kategori…">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="stock">Stok</label>
                <input id="stock" type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" required>
            </div>

            <div class="field">
                <label for="rack_id">Rak</label>
                <select id="rack_id" name="rack_id" data-combobox data-placeholder="Ketik nama rak…">
                    <option value="">Pilih rak (opsional)</option>
                    @foreach ($racks as $rack)
                        <option value="{{ $rack->id }}" @selected(old('rack_id', $book->rack_id) == $rack->id)>{{ $rack->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="summary-box" style="margin-top:16px;">
            <p>Status saat ini: <strong>{{ str_replace('_', ' ', ucfirst($book->status)) }}</strong>.</p>
            <p>Status akan otomatis diperbarui berdasarkan stok setelah data disimpan.</p>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan Perubahan</button>
            <a class="btn secondary" href="{{ route('books.index') }}">Batal</a>
        </div>
    </form>
@endsection

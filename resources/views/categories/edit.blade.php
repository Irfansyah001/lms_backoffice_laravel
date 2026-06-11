@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('subtitle', 'Perbarui data kategori buku.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama Kategori</label>
                <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required>
            </div>

            <div class="field full">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description">{{ old('description', $category->description) }}</textarea>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan Perubahan</button>
            <a class="btn secondary" href="{{ route('categories.index') }}">Batal</a>
        </div>
    </form>
@endsection

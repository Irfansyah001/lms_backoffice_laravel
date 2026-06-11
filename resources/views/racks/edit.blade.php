@extends('layouts.app')

@section('title', 'Edit Rak')
@section('subtitle', 'Perbarui data rak penyimpanan buku.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('racks.update', $rack) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama Rak</label>
                <input id="name" type="text" name="name" value="{{ old('name', $rack->name) }}" required>
            </div>

            <div class="field full">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description">{{ old('description', $rack->description) }}</textarea>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan Perubahan</button>
            <a class="btn secondary" href="{{ route('racks.index') }}">Batal</a>
        </div>
    </form>
@endsection

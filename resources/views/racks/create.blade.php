@extends('layouts.app')

@section('title', 'Tambah Rak')
@section('subtitle', 'Masukkan nama dan deskripsi rak penyimpanan buku.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('racks.store') }}">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama Rak</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Rak A1" required>
            </div>

            <div class="field full">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn secondary" href="{{ route('racks.index') }}">Batal</a>
        </div>
    </form>
@endsection

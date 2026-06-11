@extends('layouts.app')

@section('title', 'Tambah Anggota')
@section('subtitle', 'Masukkan data anggota perpustakaan.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('members.store') }}">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
            </div>

            <div class="field">
                <label for="phone">Nomor Telepon</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}">
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </div>

            <div class="field full">
                <label for="address">Alamat</label>
                <textarea id="address" name="address">{{ old('address') }}</textarea>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn secondary" href="{{ route('members.index') }}">Batal</a>
        </div>
    </form>
@endsection

@extends('layouts.app')

@section('title', 'Tambah User')
@section('subtitle', 'Buat akun Admin atau Pustakawan.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="field">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <div class="field">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="pustakawan" @selected(old('role', 'pustakawan') === 'pustakawan')>Pustakawan</option>
                </select>
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn secondary" href="{{ route('users.index') }}">Batal</a>
        </div>
    </form>
@endsection

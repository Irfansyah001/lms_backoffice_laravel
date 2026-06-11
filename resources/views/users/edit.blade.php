@extends('layouts.app')

@section('title', 'Edit User')
@section('subtitle', 'Perbarui data akun back-office.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="name">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="field">
                <label for="password">Password Baru</label>
                <input id="password" type="password" name="password">
            </div>

            <div class="field">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input id="password_confirmation" type="password" name="password_confirmation">
            </div>

            <div class="field">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    <option value="pustakawan" @selected(old('role', $user->role) === 'pustakawan')>Pustakawan</option>
                </select>
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="aktif" @selected(old('status', $user->status) === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $user->status) === 'nonaktif')>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan Perubahan</button>
            <a class="btn secondary" href="{{ route('users.index') }}">Batal</a>
        </div>
    </form>
@endsection

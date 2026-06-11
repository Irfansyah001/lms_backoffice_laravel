@extends('layouts.app')

@section('title', 'Edit Anggota')
@section('subtitle', 'Perbarui data anggota perpustakaan.')

@section('content')
    <form class="form-card" method="POST" action="{{ route('members.update', $member) }}">
        @csrf
        @method('PUT')
        <div class="summary-box" style="margin-bottom:16px;">
            <p><strong>Nomor Anggota:</strong> {{ $member->member_code }}</p>
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="name">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name', $member->name) }}" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $member->email) }}">
            </div>

            <div class="field">
                <label for="phone">Nomor Telepon</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $member->phone) }}">
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="aktif" @selected(old('status', $member->status) === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $member->status) === 'nonaktif')>Nonaktif</option>
                </select>
            </div>

            <div class="field full">
                <label for="address">Alamat</label>
                <textarea id="address" name="address">{{ old('address', $member->address) }}</textarea>
            </div>
        </div>

        <div class="actions" style="margin-top:18px;">
            <button class="btn" type="submit">Simpan Perubahan</button>
            <a class="btn secondary" href="{{ route('members.index') }}">Batal</a>
        </div>
    </form>
@endsection

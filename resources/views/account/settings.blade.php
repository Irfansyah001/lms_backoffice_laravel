@extends('layouts.app')

@section('title', 'Pengaturan Akun')
@section('subtitle', 'Kelola password akun yang sedang digunakan.')

@section('content')
    <div class="grid two">
        <section class="card">
            <div class="section-head">
                <h2>Informasi Akun</h2>
            </div>

            <dl class="detail-list">
                <dt>Nama</dt>
                <dd>{{ auth()->user()->name }}</dd>

                <dt>Email</dt>
                <dd>{{ auth()->user()->email }}</dd>

                <dt>Role</dt>
                <dd><span class="badge success">{{ ucfirst(auth()->user()->role) }}</span></dd>

                <dt>Status</dt>
                <dd>{{ ucfirst(auth()->user()->status) }}</dd>
            </dl>
        </section>

        <form class="form-card" method="POST" action="{{ route('account.password.update') }}">
            @csrf
            @method('PUT')

            <div class="section-head">
                <h2>Ganti Password</h2>
            </div>

            <div class="form-grid">
                <div class="field full">
                    <label for="current_password">Password Lama</label>
                    <input id="current_password" type="password" name="current_password" required>
                </div>

                <div class="field">
                    <label for="password">Password Baru</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="field">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>
            </div>

            <div class="actions" style="margin-top:18px;">
                <button class="btn" type="submit">Simpan Password</button>
            </div>
        </form>
    </div>
@endsection

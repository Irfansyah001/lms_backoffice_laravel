@extends('layouts.app')

@section('title', 'Manajemen User')
@section('subtitle', 'Admin dapat menambah, mengubah, dan menghapus akun back-office.')

@section('content')
    <div class="toolbar">
        <form class="search-form" method="GET" action="{{ route('users.index') }}">
            <div class="field">
                <label for="q">Cari user</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Nama atau email">
            </div>
            <button class="btn secondary" type="submit">Cari</button>
        </form>

        <a class="btn" href="{{ route('users.create') }}">Tambah User</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge success">{{ ucfirst($user->role) }}</span></td>
                        <td><span class="badge {{ $user->status === 'aktif' ? 'success' : 'danger' }}">{{ ucfirst($user->status) }}</span></td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary small" href="{{ route('users.edit', $user) }}">Edit</a>
                                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger small" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Data user belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $users])
@endsection

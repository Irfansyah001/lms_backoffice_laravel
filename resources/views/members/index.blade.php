@extends('layouts.app')

@section('title', 'Manajemen Anggota')
@section('subtitle', 'Kelola data anggota perpustakaan dan status keanggotaannya.')

@section('content')
    <div class="toolbar">
        <form class="search-form" method="GET" action="{{ route('members.index') }}">
            <div class="field">
                <label for="q">Cari anggota</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Nama atau nomor anggota">
            </div>
            <button class="btn secondary" type="submit">Cari</button>
        </form>

        <a class="btn" href="{{ route('members.create') }}">Tambah Anggota</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No. Anggota</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                    <tr>
                        <td>{{ $member->member_code }}</td>
                        <td>
                            <strong>{{ $member->name }}</strong><br>
                            <span class="muted">{{ $member->address ?: '-' }}</span>
                        </td>
                        <td>{{ $member->email ?: '-' }}</td>
                        <td>{{ $member->phone ?: '-' }}</td>
                        <td><span class="badge {{ $member->status === 'aktif' ? 'success' : 'danger' }}">{{ ucfirst($member->status) }}</span></td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary small" href="{{ route('members.show', $member) }}">Detail</a>
                                <a class="btn secondary small" href="{{ route('members.edit', $member) }}">Edit</a>
                                <form method="POST" action="{{ route('members.destroy', $member) }}" onsubmit="return confirm('Hapus anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger small" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Data anggota belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $members])
@endsection

@extends('layouts.app')

@section('title', 'Rak Buku')
@section('subtitle', 'Kelola daftar rak penyimpanan koleksi buku.')

@section('content')
    <div class="toolbar">
        <form class="search-form" method="GET" action="{{ route('racks.index') }}">
            <div class="field">
                <label for="q">Cari rak</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Nama rak">
            </div>
            <button class="btn secondary" type="submit">Cari</button>
        </form>

        <a class="btn" href="{{ route('racks.create') }}">Tambah Rak</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Rak</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Buku</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($racks as $rack)
                    <tr>
                        <td>{{ $rack->name }}</td>
                        <td>{{ $rack->description ?: '-' }}</td>
                        <td><span class="badge">{{ $rack->books_count }}</span></td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary small" href="{{ route('racks.edit', $rack) }}">Edit</a>
                                <form method="POST" action="{{ route('racks.destroy', $rack) }}" onsubmit="return confirm('Hapus rak ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger small" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Data rak belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $racks])
@endsection

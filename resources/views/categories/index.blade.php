@extends('layouts.app')

@section('title', 'Kategori Buku')
@section('subtitle', 'Kelompokkan buku agar data koleksi lebih rapi.')

@section('content')
    <div class="toolbar">
        <form class="search-form" method="GET" action="{{ route('categories.index') }}">
            <div class="field">
                <label for="q">Cari kategori</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Nama kategori">
            </div>
            <button class="btn secondary" type="submit">Cari</button>
        </form>

        <a class="btn" href="{{ route('categories.create') }}">Tambah Kategori</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Buku</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?: '-' }}</td>
                        <td><span class="badge">{{ $category->books_count }}</span></td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary small" href="{{ route('categories.edit', $category) }}">Edit</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger small" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Data kategori belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $categories])
@endsection

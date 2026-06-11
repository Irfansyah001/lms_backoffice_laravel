@extends('layouts.app')

@section('title', 'Manajemen Buku')
@section('subtitle', 'Kelola judul, penulis, kategori, stok, rak, dan status buku.')

@section('content')
    <div class="toolbar">
        <form class="search-form" method="GET" action="{{ route('books.index') }}">
            <div class="field">
                <label for="q">Cari buku</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Judul atau penulis">
            </div>
            <button class="btn secondary" type="submit">Cari</button>
        </form>

        <a class="btn" href="{{ route('books.create') }}">Tambah Buku</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Rak</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>
                            <strong>{{ $book->title }}</strong><br>
                            <span class="muted">{{ $book->publisher ?: '-' }} @if($book->publication_year) / {{ $book->publication_year }} @endif</span>
                        </td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->category->name }}</td>
                        <td><span class="badge {{ $book->stock <= 2 ? 'warning' : 'success' }}">{{ $book->stock }}</span></td>
                        <td>{{ $book->shelf_location ?: '-' }}</td>
                        <td><span class="badge {{ $book->status === 'tersedia' ? 'success' : 'danger' }}">{{ str_replace('_', ' ', ucfirst($book->status)) }}</span></td>
                        <td>
                            <div class="actions">
                                <a class="btn secondary small" href="{{ route('books.show', $book) }}">Detail</a>
                                <a class="btn secondary small" href="{{ route('books.edit', $book) }}">Edit</a>
                                <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('Hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger small" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">Data buku belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $books])
@endsection

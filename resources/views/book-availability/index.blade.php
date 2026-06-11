@extends('layouts.app')

@section('title', 'Cek Ketersediaan Buku')
@section('subtitle', 'Cari buku berdasarkan judul, penulis, atau kategori tanpa mengubah data buku.')

@section('content')
    <div class="toolbar">
        <form class="search-form" method="GET" action="{{ route('book-availability.index') }}">
            <div class="field">
                <label for="q">Cari buku</label>
                <input id="q" type="text" name="q" value="{{ $search }}" placeholder="Judul, penulis, atau kategori">
            </div>
            <button class="btn secondary" type="submit">Cari</button>
            @if ($search)
                <a class="btn secondary" href="{{ route('book-availability.index') }}">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Rak</th>
                    <th>Stok</th>
                    <th>Ketersediaan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    @php
                        $isAvailable = $book->status === 'tersedia' && $book->stock > 0;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $book->title }}</strong><br>
                            <span class="muted">{{ $book->publisher ?: '-' }} @if($book->publication_year) / {{ $book->publication_year }} @endif</span>
                        </td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->category->name }}</td>
                        <td>{{ $book->rack?->name ?: '-' }}</td>
                        <td><span class="badge {{ $book->stock > 0 ? 'success' : 'danger' }}">{{ $book->stock }}</span></td>
                        <td>
                            <span class="badge {{ $isAvailable ? 'success' : 'danger' }}">
                                {{ $isAvailable ? 'Tersedia' : 'Tidak tersedia' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Buku tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $books])
@endsection

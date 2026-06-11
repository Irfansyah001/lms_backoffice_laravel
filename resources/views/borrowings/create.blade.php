@extends('layouts.app')

@section('title', 'Tambah Peminjaman')
@section('subtitle', 'Ikuti langkah berurutan: cari anggota, cari buku, lalu tentukan tanggal. Stok buku dicek otomatis sebelum disimpan.')

@section('content')
    @include('borrowings.partials.form', [
        'borrowing' => null,
        'action' => route('borrowings.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan',
    ])
@endsection

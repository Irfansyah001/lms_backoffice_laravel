@extends('layouts.app')

@section('title', 'Edit Peminjaman')
@section('subtitle', 'Perbarui transaksi yang masih aktif. Anggota dan buku dapat dicari ulang.')

@section('content')
    @include('borrowings.partials.form', [
        'borrowing' => $borrowing,
        'action' => route('borrowings.update', $borrowing),
        'method' => 'PUT',
        'submitLabel' => 'Simpan Perubahan',
    ])
@endsection

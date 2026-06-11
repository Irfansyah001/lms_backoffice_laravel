@extends('layouts.app')

@section('title', 'Detail Anggota')
@section('subtitle', 'Lihat data anggota, audit perubahan, dan riwayat peminjaman.')

@section('content')
    <section class="form-card">
        <dl class="detail-list">
            <dt>Nomor Anggota</dt>
            <dd>{{ $member->member_code }}</dd>

            <dt>Nama</dt>
            <dd>{{ $member->name }}</dd>

            <dt>Email</dt>
            <dd>{{ $member->email ?: '-' }}</dd>

            <dt>Telepon</dt>
            <dd>{{ $member->phone ?: '-' }}</dd>

            <dt>Alamat</dt>
            <dd>{{ $member->address ?: '-' }}</dd>

            <dt>Status</dt>
            <dd><span class="badge {{ $member->status === 'aktif' ? 'success' : 'danger' }}">{{ ucfirst($member->status) }}</span></dd>

            <dt>Dibuat oleh</dt>
            <dd>{{ $member->createdBy?->name ?: '-' }}</dd>

            <dt>Terakhir diubah oleh</dt>
            <dd>{{ $member->updatedBy?->name ?: '-' }}</dd>
        </dl>

        <div class="actions" style="margin-top:18px;">
            <a class="btn secondary" href="{{ route('members.index') }}">Kembali</a>
            <a class="btn" href="{{ route('members.edit', $member) }}">Edit</a>
        </div>
    </section>

    <div class="table-wrap">
        <div class="section-head">
            <h2>Riwayat Peminjaman</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($member->borrowings as $borrowing)
                    <tr>
                        <td>{{ $borrowing->book->title }}</td>
                        <td>{{ $borrowing->borrowed_at->format('d/m/Y') }}</td>
                        <td>{{ $borrowing->due_date->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($borrowing->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Anggota belum memiliki riwayat peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

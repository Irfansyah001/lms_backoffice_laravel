@startuml Activity_ManajemenPeminjaman

|Pengguna|
start
:Buka menu Manajemen Peminjaman;

|BorrowingController|
:Tampilkan daftar peminjaman\n(with member, book);

|Pengguna|
:Pilih aksi;

switch (Aksi?)
case (Tambah Peminjaman)
  |Pengguna|
  :Isi form:\nanggota, buku,\ntanggal pinjam, jatuh tempo;
  :Submit;

  |BorrowingController|
  :Validasi data input;
  if (Valid?) then (tidak)
    :Error validasi;
    |Pengguna|
    :Tampilkan error;
    stop
  else (ya)
  endif

  |DB Transaction|
  :Mulai transaksi;

  |Model Member|
  :findOrFail(member_id);

  |BorrowingController|
  if (Anggota aktif?) then (tidak)
    :Error "Anggota harus aktif";
    |Pengguna|
    :Tampilkan error;
    stop
  else (ya)
  endif

  |Model Book|
  :lockForUpdate()\n->findOrFail(book_id);
  :isAvailable();

  if (Stok tersedia?) then (tidak)
    |BorrowingController|
    :Error "Stok tidak tersedia";
    |Pengguna|
    :Tampilkan error;
    stop
  else (ya)
  endif

  |BorrowingController|
  if (Peminjaman aktif < 3?) then (tidak)
    :Error "Batas 3 peminjaman";
    |Pengguna|
    :Tampilkan error;
    stop
  else (ya)
  endif

  if (Buku belum dipinjam\nanggota ini?) then (tidak)
    :Error "Buku masih dipinjam";
    |Pengguna|
    :Tampilkan error;
    stop
  else (ya)
  endif

  |Model Borrowing|
  :Borrowing::create(\nstatus = 'dipinjam');

  |Model Book|
  :decrement('stock');
  :syncAvailabilityStatus();

  |DB Transaction|
  :Commit transaksi;

  |BorrowingController|
  :Redirect + sukses;

case (Edit Peminjaman)
  |Pengguna|
  :Ubah data peminjaman;
  :Submit;
  |BorrowingController|
  :Cek isReturned();
  if (Sudah dikembalikan?) then (ya)
    :Error "Tidak bisa diedit";
    |Pengguna|
    :Tampilkan error;
    stop
  else (tidak)
  endif
  |BorrowingController|
  :Validasi & proses update\n(termasuk swap buku jika beda);
  |Model Borrowing|
  :Update record;
  |BorrowingController|
  :Redirect + sukses;

case (Lihat Detail)
  |BorrowingController|
  :Load data lengkap\n(member, book.category,\nbook.rack, createdBy);
  |Pengguna|
  :Melihat detail peminjaman;
  stop

case (Batalkan Peminjaman)
  |Pengguna|
  :Klik Batalkan;
  |BorrowingController|
  :abort_unless(isAdmin(), 403);
  if (Bukan Admin?) then (ya)
    :403 Forbidden;
    |Pengguna|
    :Tampilkan error 403;
    stop
  else (tidak)
  endif
  :Cek isClosed();
  if (Sudah tertutup?) then (ya)
    :Error "Tidak bisa dibatalkan";
    |Pengguna|
    :Tampilkan error;
    stop
  else (tidak)
  endif
  |DB Transaction|
  :Mulai transaksi;
  |Model Book|
  :increment('stock');
  :syncAvailabilityStatus();
  |Model Borrowing|
  :Update status = 'dibatalkan';
  |DB Transaction|
  :Commit;
  |BorrowingController|
  :Redirect + sukses;
endswitch

|Pengguna|
:Melihat hasil aksi;
stop

@enduml
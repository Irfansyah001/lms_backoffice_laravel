@startuml Activity_ManajemenPengembalian

|Pengguna|
start
:Buka menu Pengembalian Buku;

|ReturnController|
:Tampilkan daftar peminjaman\n(with member, book);

|Pengguna|
:Pilih transaksi yang ingin dikembalikan;
:Klik Proses Pengembalian;

|ReturnController|
:GET /returns/{borrowing}/edit;

|Model Borrowing|
:isClosed();

if (Transaksi sudah tertutup?) then (ya)
  |ReturnController|
  :Redirect + error\n"Transaksi sudah selesai/dibatalkan";
  |Pengguna|
  :Tampilkan pesan error;
  stop
else (tidak)
endif

|ReturnController|
:Tampilkan form pengembalian;

|Pengguna|
:Isi tanggal pengembalian;
:Submit;

|ReturnController|
:PUT /returns/{borrowing};
:isClosed() cek ulang;

if (Sudah tertutup?) then (ya)
  :Redirect + error;
  |Pengguna|
  :Tampilkan error;
  stop
else (tidak)
endif

:Validasi returned_at:\n>= borrowed_at, <= hari ini;

if (Tanggal valid?) then (tidak)
  :ValidationException;
  |Pengguna|
  :Tampilkan error;
  stop
else (ya)
endif

|DB Transaction|
:Mulai transaksi;

|ReturnController|
:Hitung keterlambatan\n(returnedAt vs dueDate);

if (Terlambat?) then (ya)
  |Model Borrowing|
  :Update returned_at\nstatus = 'terlambat';
else (tidak)
  |Model Borrowing|
  :Update returned_at\nstatus = 'dikembalikan';
endif

|Model Book|
:lockForUpdate() findOrFail(book_id);
:increment('stock');
:syncAvailabilityStatus();

|DB Transaction|
:Commit transaksi;

|ReturnController|
:Redirect + pesan sukses;

|Pengguna|
:Melihat konfirmasi\npengembalian berhasil;
stop

@enduml
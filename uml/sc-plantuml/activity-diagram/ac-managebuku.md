@startuml Activity_ManajemenBuku

|Admin|
start
:Buka menu Manajemen Buku;

|BookController|
:Tampilkan daftar buku\n(with category, rack);

|Admin|
:Pilih aksi;

switch (Aksi?)
case (Tambah Buku)
  |Admin|
  :Isi form:\njudul, penulis, penerbit,\ntahun, kategori, rak, stok;
  :Submit;
  |BookController|
  :Validasi data;
  if (Valid?) then (tidak)
    :Error;
    |Admin|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model Book|
  :Simpan buku\nstatus = tersedia;
  |BookController|
  :Redirect + sukses;

case (Edit Buku)
  |Admin|
  :Ubah data buku\n(termasuk ganti rak);
  :Submit;
  |BookController|
  :Validasi data;
  if (Valid?) then (tidak)
    :Error;
    |Admin|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model Book|
  :Update data buku;
  :syncAvailabilityStatus();
  |BookController|
  :Redirect + sukses;

case (Lihat Detail)
  |BookController|
  :Tampilkan detail buku\n(with category, rack, borrowings);
  |Admin|
  :Melihat detail buku;
  stop

case (Hapus Buku)
  |Admin|
  :Konfirmasi hapus;
  |BookController|
  :Cek relasi peminjaman\n(restrictOnDelete);
  if (Ada peminjaman aktif?) then (ya)
    :Error restrict;
    |Admin|
    :Tampilkan error;
    stop
  else (tidak)
  endif
  |Model Book|
  :Hapus buku;
  |BookController|
  :Redirect + sukses;
endswitch

|Admin|
:Melihat hasil aksi;
stop

@enduml
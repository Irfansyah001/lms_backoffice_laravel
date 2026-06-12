@startuml Activity_ManajemenKategori

|Admin|
start
:Buka menu Manajemen Kategori;

|CategoryController|
:Tampilkan daftar kategori;

|Admin|
:Pilih aksi;

switch (Aksi?)
case (Tambah)
  |Admin|
  :Isi nama & deskripsi;
  :Submit;
  |CategoryController|
  :Validasi nama unik;
  if (Valid?) then (tidak)
    :Error;
    |Admin|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model Category|
  :Simpan kategori;
  |CategoryController|
  :Redirect + sukses;

case (Edit)
  |Admin|
  :Ubah data;
  :Submit;
  |CategoryController|
  :Validasi;
  if (Valid?) then (tidak)
    :Error;
    |Admin|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model Category|
  :Update kategori;
  |CategoryController|
  :Redirect + sukses;

case (Hapus)
  |Admin|
  :Konfirmasi hapus;
  |CategoryController|
  :Cek relasi buku (restrictOnDelete);
  if (Masih ada buku?) then (ya)
    :Error restrict delete;
    |Admin|
    :Tampilkan error;
    stop
  else (tidak)
  endif
  |Model Category|
  :Hapus kategori;
  |CategoryController|
  :Redirect + sukses;
endswitch

|Admin|
:Melihat hasil aksi;
stop

@enduml
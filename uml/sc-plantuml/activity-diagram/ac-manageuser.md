@startuml Activity_ManajemenUser

|Admin|
start
:Buka menu Manajemen User;

|UserController|
:Query & tampilkan\ndaftar user;

|Admin|
:Pilih aksi;

switch (Aksi?)
case (Tambah)
  |Admin|
  :Isi form:\nnama, email, password,\nrole, status;
  :Submit;
  |UserController|
  :Validasi data;
  if (Valid?) then (tidak)
    :Error validasi;
    |Admin|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model User|
  :Simpan user baru;
  |UserController|
  :Redirect + sukses;

case (Edit)
  |Admin|
  :Ubah data user;
  :Submit;
  |UserController|
  :Validasi data;
  if (Valid?) then (tidak)
    :Error validasi;
    |Admin|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model User|
  :Update data user;
  |UserController|
  :Redirect + sukses;

case (Hapus)
  |Admin|
  :Konfirmasi hapus;
  |Model User|
  :Hapus user dari database;
  |UserController|
  :Redirect + sukses;
endswitch

|Admin|
:Melihat hasil aksi;
stop

@enduml
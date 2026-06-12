@startuml Activity_ManajemenAnggota

|Pengguna|
start
:Buka menu Manajemen Anggota;

|MemberController|
:Tampilkan daftar anggota;

|Pengguna|
:Pilih aksi;

switch (Aksi?)
case (Tambah)
  |Pengguna|
  :Isi form:\nnama, email, telepon, alamat;
  :Submit;
  |MemberController|
  :Validasi data;
  if (Valid?) then (tidak)
    :Error;
    |Pengguna|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model Member|
  :Simpan anggota ke database;
  :booted() → generate\nmember_code otomatis\n(AGT-001, AGT-002, ...);
  |MemberController|
  :Redirect + sukses;

case (Lihat Detail)
  |MemberController|
  :Load data + riwayat peminjaman;
  |Pengguna|
  :Melihat detail anggota;
  stop

case (Edit)
  |Pengguna|
  :Ubah data;
  :Submit;
  |MemberController|
  :Validasi data;
  if (Valid?) then (tidak)
    :Error;
    |Pengguna|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model Member|
  :Update data anggota;
  |MemberController|
  :Redirect + sukses;

case (Hapus)
  |Pengguna|
  :Konfirmasi hapus;
  |MemberController|
  :Cek relasi peminjaman\n(restrictOnDelete);
  if (Ada peminjaman?) then (ya)
    :Error restrict;
    |Pengguna|
    :Tampilkan error;
    stop
  else (tidak)
  endif
  |Model Member|
  :Hapus anggota;
  |MemberController|
  :Redirect + sukses;
endswitch

|Pengguna|
:Melihat hasil aksi;
stop

@enduml
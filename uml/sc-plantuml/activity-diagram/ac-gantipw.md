@startuml Activity_GantiPassword

|Pengguna|
start
:Buka menu Pengaturan Akun;
:Isi password lama,\npassword baru,\nkonfirmasi password baru;
:Klik Simpan;

|AccountController|
:Terima PUT /account/password;
:Validasi input;

if (Format valid?) then (tidak)
  :Kembalikan error validasi;
  |Pengguna|
  :Tampilkan error form;
  stop
else (ya)
endif

:Verifikasi password lama\ndengan Hash::check();

if (Password lama benar?) then (tidak)
  :Kembalikan error\n"Password lama salah";
  |Pengguna|
  :Tampilkan pesan error;
  stop
else (ya)
endif

|Model User|
:Hash password baru;
:Update password ke database;

|AccountController|
:Redirect + pesan sukses;

|Pengguna|
:Melihat konfirmasi\n"Password berhasil diubah";
stop

@enduml
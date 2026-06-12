@startuml Activity_Login

|Pengguna|
start
:Buka halaman login;
:Isi email & password;
:Klik tombol Login;

|AuthController|
:Terima POST /login;
:Cek RateLimiter;

if (Terlalu banyak percobaan\n>= 5 kali?) then (ya)
  :Lempar error\n"Coba lagi dalam N detik";
  |Pengguna|
  :Tampilkan pesan\nbatas percobaan;
  stop
else (tidak)
endif

:Validasi format\nemail & password;

if (Format valid?) then (tidak)
  :Kembalikan error validasi;
  |Pengguna|
  :Tampilkan error form;
  stop
else (ya)
endif

|Auth Facade|
:Cek kredensial + status aktif\ndi tabel users;

if (Kredensial benar &\nakun aktif?) then (tidak)
  |AuthController|
  :hit(RateLimiter);
  :Kembalikan error login;
  |Pengguna|
  :Tampilkan pesan\nemail/password salah;
  stop
else (ya)
endif

|AuthController|
:clear(RateLimiter);
:session()->regenerate();
:Redirect → Dashboard;

|Pengguna|
:Melihat halaman Dashboard;
stop

@enduml
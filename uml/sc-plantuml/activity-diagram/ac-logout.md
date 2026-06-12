@startuml Activity_Logout

|Pengguna|
start
:Klik tombol Logout;

|AuthController|
:Terima POST /logout;
:Middleware auth verifikasi sesi;
:Auth::logout();
:session()->invalidate();
:session()->regenerateToken();
:Redirect → /login + pesan sukses;

|Pengguna|
:Melihat halaman Login;
stop

@enduml
@startuml Sequence_Login
skinparam sequenceMessageAlign center

actor "Pengguna" as user
participant "Halaman Login" as view
participant "AuthController" as ctrl
participant "RateLimiter" as rl
participant "Auth Facade" as auth
database "Database" as db

user -> view : Buka halaman login
user -> view : Isi email & password\nlalu klik Login
view -> ctrl : POST /login

ctrl -> rl : tooManyAttempts(email|ip, 5)?
alt Terlalu banyak percobaan
  rl --> ctrl : true
  ctrl --> view : ValidationException\n"Coba lagi dalam N detik"
  view --> user : Tampilkan pesan error
else Masih diizinkan
  rl --> ctrl : false
  ctrl -> ctrl : Validasi format email & password
  ctrl -> auth : attempt({email, password, status:'aktif'}, remember)
  auth -> db : Cek kredensial + status akun
  alt Login gagal
    db --> auth : tidak ditemukan
    auth --> ctrl : false
    ctrl -> rl : hit(email|ip)
    ctrl --> view : Error "Email/password salah\natau akun belum aktif"
    view --> user : Tampilkan pesan error
  else Login berhasil
    db --> auth : data user valid
    auth --> ctrl : true
    ctrl -> rl : clear(email|ip)
    ctrl -> ctrl : session()->regenerate()
    ctrl --> view : redirect → /dashboard
    view --> user : Tampilkan Dashboard
  end
end

@enduml
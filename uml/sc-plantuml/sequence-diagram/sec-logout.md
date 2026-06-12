@startuml Sequence_Logout
skinparam sequenceMessageAlign center

actor "Pengguna" as user
participant "Browser" as view
participant "AuthController" as ctrl
participant "Auth Facade" as auth

user -> view : Klik tombol Logout
view -> ctrl : POST /logout\n[middleware: auth]
ctrl -> auth : Auth::logout()
auth --> ctrl : sesi dihapus
ctrl -> ctrl : session()->invalidate()
ctrl -> ctrl : session()->regenerateToken()
ctrl --> view : redirect → /login\n+ pesan sukses
view --> user : Tampilkan halaman Login

@enduml
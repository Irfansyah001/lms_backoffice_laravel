@startuml
left to right direction
skinparam actorStyle awesome
skinparam packageStyle rectangle

actor "Admin" as Admin
actor "Pustakawan" as Pustakawan

rectangle "LMS Backoffice" {

(Login) as Login
(Logout) as Logout


(Pengaturan Akun) as Akun
(Ganti Password) as Password

(Cek Ketersediaan Buku) as Cek
(Manajemen Anggota) as Anggota
(Manajemen Peminjaman Buku) as Pinjam
(Manajemen Pengembalian Buku) as Kembali

(Manajemen User) as User
(Manajemen Buku) as Buku
(Manajemen Kategori Buku) as Kategori
(Manajemen Rak Buku) as Rak

}

'====================
' Admin
'====================
Admin --> Akun
Admin --> Cek
Admin --> Anggota
Admin --> Pinjam
Admin --> Kembali
Admin --> User
Admin --> Buku
Admin --> Kategori
Admin --> Rak

'====================
' Pustakawan
'====================
Pustakawan --> Akun
Pustakawan --> Cek
Pustakawan --> Anggota
Pustakawan --> Pinjam
Pustakawan --> Kembali

'====================
' Include Login
'====================
Akun ..> Login : <<include>>
Cek ..> Login : <<include>>
Anggota ..> Login : <<include>>
Pinjam ..> Login : <<include>>
Kembali ..> Login : <<include>>
User ..> Login : <<include>>
Buku ..> Login : <<include>>
Kategori ..> Login : <<include>>
Rak ..> Login : <<include>>

'====================
' Extend
'====================
Logout ..> Login : <<extend>>
Password ..> Akun : <<extend>>

@enduml

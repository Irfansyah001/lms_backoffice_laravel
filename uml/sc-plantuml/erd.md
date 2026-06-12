@startuml
title ERD (Conceptual Design) Library Management System

hide circle
skinparam linetype ortho

entity "Users" {
  * id
  --
  name
  email
  password
  role
  status
}

entity "Categories" {
  * id
  --
  name
  description
}

entity "Racks" {
  * id
  --
  name
  description
}

entity "Books" {
  * id
  --
  title
  author
  publisher
  publication_year
  stock
  status
}

entity "Members" {
  * id
  --
  member_code
  name
  email
  phone
  address
  status
}

entity "Borrowings" {
  * id
  --
  borrowed_at
  due_date
  returned_at
  status
}

' --- Relasi Bisnis ---
Categories "1" -- "0..*" Books : "mengelompokkan"
Racks "0..1" -- "0..*" Books : "menyimpan"
Members "1" -- "0..*" Borrowings : "melakukan"
Books "1" -- "0..*" Borrowings : "dipinjam lewat"

note right of Users : role: admin, pustakawan\nstatus: aktif, nonaktif
note right of Books : status: tersedia, tidak_tersedia
note right of Members : status: aktif, nonaktif
note right of Borrowings : status: dipinjam, dikembalikan, terlambat, dibatalkan
@enduml
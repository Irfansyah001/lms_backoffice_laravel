@startuml
title ERD Library Management System Backoffice

hide circle
skinparam linetype ortho

entity "users" as users {
  * id : BIGINT <<PK>>
  --
  name : VARCHAR
  email : VARCHAR <<UNIQUE>>
  email_verified_at : TIMESTAMP <<NULL>>
  password : VARCHAR
  role : VARCHAR
  status : VARCHAR
  created_by : BIGINT <<FK, NULL>>
  updated_by : BIGINT <<FK, NULL>>
  remember_token : VARCHAR <<NULL>>
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "categories" as categories {
  * id : BIGINT <<PK>>
  --
  name : VARCHAR <<UNIQUE>>
  description : TEXT <<NULL>>
  created_by : BIGINT <<FK, NULL>>
  updated_by : BIGINT <<FK, NULL>>
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "racks" as racks {
  * id : BIGINT <<PK>>
  --
  name : VARCHAR <<UNIQUE>>
  description : VARCHAR <<NULL>>
  created_by : BIGINT <<FK, NULL>>
  updated_by : BIGINT <<FK, NULL>>
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "books" as books {
  * id : BIGINT <<PK>>
  --
  category_id : BIGINT <<FK>>
  rack_id : BIGINT <<FK, NULL>>
  title : VARCHAR
  author : VARCHAR
  publisher : VARCHAR <<NULL>>
  publication_year : SMALLINT <<NULL>>
  stock : INTEGER
  status : VARCHAR
  created_by : BIGINT <<FK, NULL>>
  updated_by : BIGINT <<FK, NULL>>
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "members" as members {
  * id : BIGINT <<PK>>
  --
  member_code : VARCHAR <<UNIQUE, NULL>>
  name : VARCHAR
  email : VARCHAR <<NULL>>
  phone : VARCHAR <<NULL>>
  address : TEXT <<NULL>>
  status : VARCHAR
  created_by : BIGINT <<FK, NULL>>
  updated_by : BIGINT <<FK, NULL>>
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "borrowings" as borrowings {
  * id : BIGINT <<PK>>
  --
  member_id : BIGINT <<FK>>
  book_id : BIGINT <<FK>>
  borrowed_at : DATE
  due_date : DATE
  returned_at : DATE <<NULL>>
  status : VARCHAR
  created_by : BIGINT <<FK, NULL>>
  updated_by : BIGINT <<FK, NULL>>
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

categories ||--o{ books : "category_id"
racks |o--o{ books : "rack_id"

members ||--o{ borrowings : "member_id"
books ||--o{ borrowings : "book_id"

users |o--o{ users : "created_by / updated_by"
users |o--o{ categories : "created_by / updated_by"
users |o--o{ racks : "created_by / updated_by"
users |o--o{ books : "created_by / updated_by"
users |o--o{ members : "created_by / updated_by"
users |o--o{ borrowings : "created_by / updated_by"

note right of users
role: admin, pustakawan
status: aktif, nonaktif
end note

note right of books
status: tersedia, tidak_tersedia
rack_id bersifat opsional
end note

note right of members
member_code dibuat otomatis
status: aktif, nonaktif
end note

note right of borrowings
status:
- dipinjam
- dikembalikan
- terlambat
- dibatalkan
end note

@enduml
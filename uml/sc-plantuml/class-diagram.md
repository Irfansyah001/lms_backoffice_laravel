@startuml
title Class Diagram - Library Management System

skinparam classAttributeIconSize 0
skinparam linetype ortho

package "Http/Controllers" {
    class DashboardController {
        +index()
    }

    class AuthController {
        +loginForm()
        +login(request: Request)
        +logout()
    }

    class AccountController {
        +index()
        +update(request: Request)
    }

    class UserController {
        +index(request: Request)
        +store(request: Request)
        +update(request: Request, id: Long)
        +destroy(id: Long)
    }

    class CategoryController {
        +index()
        +store(request: Request)
        +update(request: Request, id: Long)
        +destroy(id: Long)
    }

    class RackController {
        +index()
        +store(request: Request)
        +update(request: Request, id: Long)
        +destroy(id: Long)
    }

    class BookController {
        +index()
        +store(request: Request)
        +update(request: Request, id: Long)
        +destroy(id: Long)
    }

    class BookAvailabilityController {
        +index(request: Request)
    }

    class MemberController {
        +index()
        +store(request: Request)
        +update(request: Request, id: Long)
        +destroy(id: Long)
    }

    class BorrowingController {
        +index()
        +store(request: Request)
        +update(request: Request, id: Long)
        +destroy(id: Long)
    }

    class ReturnController {
        +index()
        +store(request: Request)
    }
}

package "Models" {
    class User {
        - id : BigInteger
        - name : String
        - email : String
        - role : String
        - status : String
    }

    class Category {
        - id : BigInteger
        - name : String
        - description : Text
        + books() : HasMany
    }

    class Rack {
        - id : BigInteger
        - name : String
        - description : String
        + books() : HasMany
    }

    class Book {
        - id : BigInteger
        - category_id : BigInteger
        - rack_id : BigInteger
        - title : String
        - author : String
        - stock : INTEGER
        - status : String
        + category() : BelongsTo
        + rack() : BelongsTo
        + borrowings() : HasMany
    }

    class Member {
        - id : BigInteger
        - member_code : String
        - name : String
        - status : String
        + borrowings() : HasMany
    }

    class Borrowing {
        - id : BigInteger
        - member_id : BigInteger
        - book_id : BigInteger
        - borrowed_at : Date
        - due_date : Date
        - status : String
        + member() : BelongsTo
        + book() : BelongsTo
    }
}

' --- Relasi Dependensi Antar Class ---
DashboardController ..> User : menggunakan
AuthController ..> User : menggunakan
AccountController ..> User : menggunakan
UserController ..> User : menggunakan

CategoryController ..> Category : menggunakan
RackController ..> Rack : menggunakan

BookController ..> Book : menggunakan
BookAvailabilityController ..> Book : menggunakan

MemberController ..> Member : menggunakan
BorrowingController ..> Borrowing : menggunakan
ReturnController ..> Borrowing : menggunakan

' --- Relasi Struktural Database ---
Category "1" -- "0..*" Book : mengelompokkan
Rack "0..1" -- "0..*" Book : menyimpan
Member "1" -- "0..*" Borrowing : melakukan
Book "1" -- "0..*" Borrowing : dipinjam lewat
@enduml
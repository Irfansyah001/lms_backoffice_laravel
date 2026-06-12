@startuml Architecture_LMS
skinparam componentStyle rectangle
skinparam component {
  BackgroundColor #FEFECE
  BorderColor #A80036
}
skinparam package {
  BackgroundColor #FFF8F8
  BorderColor #A80036
}
top to bottom direction

actor "Admin / Pustakawan\n(Browser)" as user

package "Lapisan Presentasi" #E8F5E9 {
  [Blade Views\nauth · dashboard · books · racks\ncategories · members\nborrowing · returns · account] as blade
  [CSS / Vite Assets] as assets
}

package "Lapisan Aplikasi — Laravel" #E3F2FD {
  [routes/web.php] as routes
  [Middleware:\nauth · active (EnsureUserIsActive)\nadmin (AdminMiddleware)] as mw

  package "Controllers" #BBDEFB {
    [AuthController] as AC
    [DashboardController] as DC
    [UserController] as UC
    [RackController] as RAC
    [CategoryController] as CC
    [BookController] as BC
    [BookAvailabilityController] as BAC
    [MemberController] as MC
    [BorrowingController] as BRC
    [ReturnController] as RC
    [AccountController] as ACC
  }
}

package "Lapisan Domain / Data" #FFF9C4 {
  package "Eloquent Models" #FFFDE7 {
    [User] as MU
    [Rack] as MR
    [Category] as MCat
    [Book] as MB
    [Member] as MMem
    [Borrowing] as MBor
  }
  [Migrations & Seeders] as migr
}

database "Database\n(MySQL / SQLite)" as db

user --> routes : HTTP Request
routes --> mw : filter
mw --> AC
mw --> DC
mw --> UC
mw --> RAC
mw --> CC
mw --> BC
mw --> BAC
mw --> MC
mw --> BRC
mw --> RC
mw --> ACC

AC  --> MU
DC  --> MU
UC  --> MU
RAC --> MR
CC  --> MCat
BC  --> MB
BC  --> MR
BC  --> MCat
BAC --> MB
MC  --> MMem
BRC --> MBor
BRC --> MB
BRC --> MMem
RC  --> MBor
RC  --> MB
ACC --> MU

MR   --> db
MCat --> db
MB   --> db
MMem --> db
MBor --> db
MU   --> db
migr --> db

AC  --> blade
DC  --> blade
UC  --> blade
RAC --> blade
CC  --> blade
BC  --> blade
BAC --> blade
MC  --> blade
BRC --> blade
RC  --> blade
ACC --> blade

blade --> assets
blade --> user : HTML Response

@enduml
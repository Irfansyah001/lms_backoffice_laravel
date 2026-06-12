@startuml Sequence_Pengembalian
skinparam sequenceMessageAlign center

actor "Admin/Pustakawan" as user
participant "Browser" as view
participant "ReturnController" as ctrl
participant "DB Transaction" as tx
participant "Model Borrowing" as borrow
participant "Model Book" as book

user -> view : Pilih transaksi peminjaman\nlalu klik Kembalikan
view -> ctrl : GET /returns/{borrowing}/edit\n[middleware: auth, active]

ctrl -> borrow : isClosed()
alt Transaksi sudah dikembalikan/dibatalkan
  borrow --> ctrl : true
  ctrl --> view : redirect /returns\n+ error "Transaksi sudah selesai"
  view --> user : Tampilkan pesan error
else Transaksi masih aktif
  borrow --> ctrl : false
  ctrl --> view : Tampilkan form pengembalian
  view --> user : Isi tanggal pengembalian

  user -> view : Submit tanggal kembali
  view -> ctrl : PUT /returns/{borrowing}

  ctrl -> borrow : isClosed() (cek ulang)
  alt Sudah tertutup (race condition)
    ctrl --> view : redirect + error
    view --> user : Tampilkan error
  else Masih aktif
    ctrl -> ctrl : Validasi returned_at:\n>= borrowed_at, <= hari ini
    alt Tanggal tidak valid
      ctrl --> view : ValidationException
      view --> user : Tampilkan error
    else Tanggal valid
      ctrl -> tx : DB::transaction begin
      ctrl -> ctrl : Hitung lateDays:\nreturnedAt > dueDate ?
      alt Terlambat
        ctrl -> borrow : update(returned_at,\nstatus='terlambat')
      else Tepat waktu
        ctrl -> borrow : update(returned_at,\nstatus='dikembalikan')
      end
      ctrl -> book : lockForUpdate()\n->findOrFail(book_id)
      ctrl -> book : increment('stock')
      ctrl -> book : refresh()->syncAvailabilityStatus()
      tx --> ctrl : commit
      ctrl --> view : redirect /returns + sukses
      view --> user : "Pengembalian berhasil dicatat"
    end
  end
end

@enduml
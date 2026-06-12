@startuml Sequence_Peminjaman
skinparam sequenceMessageAlign center

actor "Admin/Pustakawan" as user
participant "Browser" as view
participant "BorrowingController" as ctrl
participant "DB Transaction" as tx
participant "Model Member" as member
participant "Model Book" as book
participant "Model Borrowing" as borrow

user -> view : Isi form peminjaman\n(anggota, buku, tanggal)
view -> ctrl : POST /borrowings\n[middleware: auth, active]
ctrl -> ctrl : validatedData(request)\n(member_id, book_id,\nborrowed_at, due_date)

alt Validasi input gagal
  ctrl --> view : ValidationException
  view --> user : Tampilkan error form
else Input valid
  ctrl -> tx : DB::transaction begin
  tx -> member : Member::findOrFail(member_id)
  member --> tx : data anggota
  tx -> book : Book::lockForUpdate()\n->findOrFail(book_id)
  book --> tx : data buku

  ctrl -> ctrl : Cek member->status == 'aktif'
  alt Anggota tidak aktif
    ctrl --> view : "Anggota harus berstatus aktif"
    view --> user : Tampilkan error
  else Anggota aktif

    ctrl -> book : isAvailable()
    alt Stok tidak tersedia
      book --> ctrl : false
      ctrl --> view : "Stok buku tidak tersedia"
      view --> user : Tampilkan error
    else Stok tersedia
      book --> ctrl : true

      ctrl -> ctrl : Cek aktiveBorrowings < 3
      alt Sudah 3 peminjaman aktif
        ctrl --> view : "Batas maksimal 3 peminjaman aktif"
        view --> user : Tampilkan error
      else Masih bisa

        ctrl -> ctrl : Cek buku belum dipinjam\nanggota yang sama
        alt Sudah ada peminjaman aktif buku ini
          ctrl --> view : "Anggota masih meminjam buku yang sama"
          view --> user : Tampilkan error
        else Belum ada

          ctrl -> borrow : Borrowing::create(\nstatus='dipinjam',\ncreated_by, updated_by)
          borrow --> tx : record tersimpan
          ctrl -> book : decrement('stock')
          ctrl -> book : refresh()->syncAvailabilityStatus()
          tx --> ctrl : commit
          ctrl --> view : redirect /borrowings + sukses
          view --> user : "Peminjaman berhasil dicatat"
        end
      end
    end
  end
end

@enduml
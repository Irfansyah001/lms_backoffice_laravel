@startuml Sequence_Rak
skinparam sequenceMessageAlign center

actor "Admin" as admin
participant "Browser" as view
participant "RackController" as ctrl
database "Tabel racks" as db
participant "Model Rack" as rack

admin -> view : Buka menu Manajemen Rak Buku
view -> ctrl : GET /racks\n[middleware: auth, active, admin]
ctrl -> db : Rack::withCount('books')->paginate(10)
db --> ctrl : daftar rak + jumlah buku
ctrl --> view : Tampilkan daftar rak
view --> admin : Lihat daftar rak

admin -> view : Pilih aksi (Tambah / Edit / Hapus)

alt Tambah Rak
  admin -> view : Isi form (nama, deskripsi)\nlalu Submit
  view -> ctrl : POST /racks
  ctrl -> ctrl : Validasi: nama required,\nunik, maks 100 karakter
  alt Validasi gagal
    ctrl --> view : ValidationException
    view --> admin : Tampilkan error
  else Valid
    ctrl -> rack : Rack::create(name, description,\ncreated_by, updated_by)
    rack -> db : INSERT INTO racks
    db --> rack : id baru
    rack --> ctrl : rak tersimpan
    ctrl --> view : redirect /racks + sukses
    view --> admin : "Rak berhasil ditambahkan"
  end

else Edit Rak
  admin -> view : Ubah data rak, Submit
  view -> ctrl : PUT /racks/{rack}
  ctrl -> ctrl : Validasi (ignore id saat ini)
  alt Validasi gagal
    ctrl --> view : ValidationException
    view --> admin : Tampilkan error
  else Valid
    ctrl -> rack : rack->update(name, description,\nupdated_by)
    rack -> db : UPDATE racks
    db --> rack : berhasil
    rack --> ctrl : data terupdate
    ctrl --> view : redirect /racks + sukses
    view --> admin : "Rak berhasil diperbarui"
  end

else Hapus Rak
  admin -> view : Klik Hapus, konfirmasi
  view -> ctrl : DELETE /racks/{rack}
  ctrl -> rack : rack->books()->exists()
  alt Masih ada buku di rak ini
    rack --> ctrl : true
    ctrl --> view : Error "Rak masih\ndigunakan oleh buku"
    view --> admin : Tampilkan pesan error
  else Rak kosong
    rack --> ctrl : false
    ctrl -> rack : rack->delete()
    rack -> db : DELETE FROM racks
    db --> rack : berhasil
    ctrl --> view : redirect /racks + sukses
    view --> admin : "Rak berhasil dihapus"
  end
end

@enduml
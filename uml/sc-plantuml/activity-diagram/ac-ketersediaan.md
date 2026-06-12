@startuml Activity_CekKetersediaan

|Pengguna|
start
:Buka menu Cek Ketersediaan Buku;

|BookAvailabilityController|
:GET /book-availability;
:Query semua buku\n(with category, rack,\nstok, status);

|Model Book|
:Ambil data dari database;

|BookAvailabilityController|
:Kirim data ke view;

|Pengguna|
:Melihat daftar buku\nbeserta status & stok;

if (Melakukan pencarian?) then (ya)
  :Masukkan kata kunci;
  |BookAvailabilityController|
  :Filter buku berdasarkan\njudul / penulis;
  |Model Book|
  :Query data yang sesuai;
  |BookAvailabilityController|
  :Kirim hasil filter;
  |Pengguna|
  :Melihat hasil pencarian;
else (tidak)
endif

stop

@enduml
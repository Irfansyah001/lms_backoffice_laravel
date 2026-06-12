@startuml Activity_ManajemenRak

|Admin|
start
:Buka menu Manajemen Rak Buku;

|RackController|
:Query Rack::withCount('books')\n->paginate(10);
:Tampilkan daftar rak;

|Admin|
:Pilih aksi;

switch (Aksi?)
case (Tambah Rak)
  |Admin|
  :Isi form:\nnama rak, deskripsi;
  :Submit;
  |RackController|
  :Validasi:\nnama required, unik,\nmaks 100 karakter;
  if (Valid?) then (tidak)
    :Kembalikan error;
    |Admin|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model Rack|
  :Rack::create(\nname, description,\ncreated_by, updated_by);
  |RackController|
  :Redirect + sukses;

case (Edit Rak)
  |Admin|
  :Ubah nama/deskripsi;
  :Submit;
  |RackController|
  :Validasi (abaikan id saat ini);
  if (Valid?) then (tidak)
    :Kembalikan error;
    |Admin|
    :Tampilkan error;
    stop
  else (ya)
  endif
  |Model Rack|
  :rack->update(\nname, description, updated_by);
  |RackController|
  :Redirect + sukses;

case (Hapus Rak)
  |Admin|
  :Konfirmasi hapus;
  |RackController|
  :Cek relasi buku;
  |Model Rack|
  :rack->books()->exists();
  if (Masih ada buku?) then (ya)
    |RackController|
    :Error\n"Rak masih digunakan oleh buku";
    |Admin|
    :Tampilkan pesan error;
    stop
  else (tidak)
  endif
  |Model Rack|
  :rack->delete();
  |RackController|
  :Redirect + sukses;
endswitch

|Admin|
:Melihat hasil aksi;
stop

@enduml
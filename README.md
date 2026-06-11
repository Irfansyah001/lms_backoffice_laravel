# Library Management System Backoffice

Project Laravel sederhana untuk tugas kampus dengan tema pengelolaan perpustakaan internal.

## Fitur Utama

- Login dan logout.
- Role Admin dan Pustakawan.
- Dashboard ringkasan sesuai role.
- CRUD user khusus Admin.
- CRUD buku dan kategori buku khusus Admin.
- Cek ketersediaan buku untuk Admin dan Pustakawan.
- CRUD anggota untuk Admin dan Pustakawan.
- CRUD peminjaman buku.
- Halaman detail buku, anggota, dan peminjaman.
- Pengaturan akun untuk mengganti password sendiri.
- Pengembalian buku dengan update stok otomatis.
- Pembatalan peminjaman oleh Admin tanpa menghapus riwayat transaksi.
- Nomor anggota dibuat otomatis oleh sistem.
- Seeder menyediakan 100 data dummy buku dan 100 data dummy anggota.

## Akun Demo

| Role | Email | Password |
| --- | --- | --- |
| Admin | admin@lms.test | password |
| Pustakawan | pustakawan@lms.test | password |

## Cara Menjalankan

1. Pastikan PHP yang dipakai adalah PHP 8.3 atau lebih baru.

```bash
php -v
```

Catatan: project ini memakai Laravel 13, jadi tidak bisa berjalan dengan PHP 7.4.

2. Install dependency PHP:

```bash
composer install
```

3. Salin file environment jika belum ada.

Windows PowerShell:

```bash
Copy-Item .env.example .env
```

macOS/Linux:

```bash
cp .env.example .env
```

4. Periksa konfigurasi database di file `.env`.

Default project ini memakai SQLite:

```env
DB_CONNECTION=sqlite
```

File database SQLite berada di `database/database.sqlite`.

5. Generate key aplikasi:

```bash
php artisan key:generate
```

6. Bersihkan cache konfigurasi:

```bash
php artisan config:clear
```

7. Jalankan migration dan seeder:

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan membuat tabel dan mengisi data awal, termasuk 2 akun demo, kategori, 100 buku dummy, 100 anggota dummy, dan transaksi contoh.

8. Jalankan server lokal:

```bash
php artisan serve
```

9. Buka aplikasi di browser:

```text
http://127.0.0.1:8000
```

## Struktur Folder Penting

```text
app/Http/Controllers      Controller untuk auth, dashboard, CRUD, dan transaksi
app/Http/Middleware       Middleware pembatasan akses role Admin dan status akun aktif
app/Models                Model Eloquent untuk User, Book, Category, Member, Borrowing
database/migrations       Struktur tabel database
database/seeders          Data awal untuk demo
resources/views           Halaman Blade
public/css/backoffice.css CSS lokal aplikasi
routes/web.php            Daftar route aplikasi
```

## Catatan Alur Sistem

- Saat peminjaman dibuat, sistem mengecek anggota aktif dan stok buku.
- Anggota maksimal memiliki 3 peminjaman aktif.
- Anggota tidak bisa meminjam buku yang sama dua kali ketika peminjaman sebelumnya masih aktif.
- Jika stok tersedia, stok buku otomatis berkurang 1.
- Saat pengembalian dicatat, stok buku otomatis bertambah 1.
- Tanggal pengembalian tidak boleh lebih awal dari tanggal pinjam dan tidak boleh melebihi hari ini.
- Jika tanggal kembali melewati jatuh tempo, status peminjaman menjadi `terlambat`.
- Status buku otomatis mengikuti stok: stok lebih dari 0 berarti `tersedia`, stok 0 berarti `tidak_tersedia`.
- Nomor anggota otomatis dibuat setelah data anggota disimpan, misalnya `AGT-001`.
- Akun Pustakawan hanya dibuat oleh Admin melalui menu Manajemen User.
- Admin dan Pustakawan dapat mengganti password sendiri melalui menu Pengaturan Akun.
- Login memiliki pembatasan percobaan agar tidak bisa dicoba terus-menerus.
- Data buku, kategori, anggota, user, dan peminjaman memiliki catatan sederhana `created_by` dan `updated_by`.
- Pustakawan tidak dapat membuka menu Manajemen User, Manajemen Buku, dan Kategori Buku.
- Pustakawan dapat mengecek ketersediaan buku tanpa mengubah data buku.
- Pustakawan difokuskan untuk mengelola anggota, peminjaman, pengembalian, dan pengecekan ketersediaan buku.

## Database

Default project ini memakai SQLite agar mudah dijalankan untuk pemula tanpa membuat database manual.

File database berada di:

```text
database/database.sqlite
```

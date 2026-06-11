<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Member;
use App\Models\Rack;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@lms.test'],
            [
                'name' => 'Admin Perpustakaan',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );

        $pustakawan = User::updateOrCreate(
            ['email' => 'pustakawan@lms.test'],
            [
                'name' => 'Pustakawan Demo',
                'password' => Hash::make('password'),
                'role' => 'pustakawan',
                'status' => 'aktif',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]
        );

        $categories = collect([
            ['name' => 'Teknologi', 'description' => 'Buku tentang komputer, aplikasi, dan teknologi informasi.'],
            ['name' => 'Pendidikan', 'description' => 'Buku pembelajaran dan pengembangan akademik.'],
            ['name' => 'Novel', 'description' => 'Buku cerita fiksi dan sastra populer.'],
            ['name' => 'Sejarah', 'description' => 'Buku sejarah nasional dan dunia.'],
            ['name' => 'Sains', 'description' => 'Buku ilmu pengetahuan alam dan riset sederhana.'],
        ])->map(fn (array $category) => Category::updateOrCreate(
            ['name' => $category['name']],
            [
                ...$category,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]
        ));

        $racks = collect([
            ['name' => 'Rak A1', 'description' => 'Rak baris A nomor 1.'],
            ['name' => 'Rak A2', 'description' => 'Rak baris A nomor 2.'],
            ['name' => 'Rak B1', 'description' => 'Rak baris B nomor 1.'],
            ['name' => 'Rak B2', 'description' => 'Rak baris B nomor 2.'],
            ['name' => 'Rak C1', 'description' => 'Rak baris C nomor 1.'],
            ['name' => 'Rak C2', 'description' => 'Rak baris C nomor 2.'],
        ])->map(fn (array $rack) => Rack::updateOrCreate(
            ['name' => $rack['name']],
            [
                ...$rack,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]
        ));

        $bookTopics = [
            'Pemrograman Web',
            'Strategi Belajar',
            'Cerita Perpustakaan',
            'Sejarah Nusantara',
            'Eksperimen Sains',
            'Algoritma Dasar',
            'Manajemen Kelas',
            'Novel Kehidupan',
            'Tokoh Nasional',
            'Fisika Sehari-hari',
            'Basis Data',
            'Psikologi Pendidikan',
            'Literasi Membaca',
            'Kerajaan Indonesia',
            'Kimia Praktis',
            'Laravel Dasar',
            'Metode Penelitian',
            'Novel Remaja',
            'Sejarah Dunia',
            'Biologi Dasar',
        ];

        $authors = [
            'Rina Pratiwi',
            'Budi Santoso',
            'Ayu Lestari',
            'Dewi Anggraini',
            'Arif Hidayat',
            'Sari Wulandari',
            'Fajar Nugroho',
            'Nadia Putri',
            'Hendra Wijaya',
            'Maya Kurnia',
        ];

        $publishers = [
            'Kampus Media',
            'Edu Press',
            'Literasi Nusantara',
            'Pustaka Mandiri',
            'Cendekia Press',
        ];

        $books = collect(range(1, 100))->map(function (int $number, int $index) use ($admin, $authors, $bookTopics, $categories, $publishers, $racks) {
            return Book::create([
                'category_id' => $categories[$index % $categories->count()]->id,
                'rack_id' => $racks[$index % $racks->count()]->id,
                'title' => $bookTopics[$index % count($bookTopics)].' Volume '.str_pad((string) $number, 3, '0', STR_PAD_LEFT),
                'author' => $authors[$index % count($authors)],
                'publisher' => $publishers[$index % count($publishers)],
                'publication_year' => 2018 + ($index % 8),
                'stock' => 2 + ($index % 7),
                'status' => 'tersedia',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
        });

        $memberNames = [
            'Dimas Saputra',
            'Siti Rahma',
            'Andi Pratama',
            'Nadia Putri',
            'Rafi Hidayat',
            'Maya Kurnia',
            'Fajar Nugroho',
            'Ayu Lestari',
            'Hendra Wijaya',
            'Sari Wulandari',
            'Rizky Maulana',
            'Intan Permata',
            'Bagus Setiawan',
            'Dewi Anggraini',
            'Arif Hidayat',
            'Putri Maharani',
            'Yoga Prasetyo',
            'Lina Marlina',
            'Galih Ramadhan',
            'Tia Kartika',
        ];

        $members = collect(range(1, 100))->map(function (int $number, int $index) use ($memberNames, $pustakawan) {
            $name = $memberNames[$index % count($memberNames)];

            return Member::create([
                'name' => $name.' '.str_pad((string) $number, 3, '0', STR_PAD_LEFT),
                'email' => 'anggota'.str_pad((string) $number, 3, '0', STR_PAD_LEFT).'@example.com',
                'phone' => '0812'.str_pad((string) $number, 8, '0', STR_PAD_LEFT),
                'address' => 'Jl. Perpustakaan No. '.$number,
                'status' => 'aktif',
                'created_by' => $pustakawan->id,
                'updated_by' => $pustakawan->id,
            ]);
        });

        $activeBorrowing = Borrowing::create([
            'member_id' => $members[0]->id,
            'book_id' => $books[0]->id,
            'borrowed_at' => now()->subDays(2)->toDateString(),
            'due_date' => now()->addDays(5)->toDateString(),
            'status' => 'dipinjam',
            'created_by' => $pustakawan->id,
            'updated_by' => $pustakawan->id,
        ]);
        $activeBorrowing->book->decrement('stock');
        $activeBorrowing->book->refresh()->syncAvailabilityStatus();

        Borrowing::create([
            'member_id' => $members[1]->id,
            'book_id' => $books[1]->id,
            'borrowed_at' => now()->subDays(12)->toDateString(),
            'due_date' => now()->subDays(5)->toDateString(),
            'returned_at' => now()->subDays(1)->toDateString(),
            'status' => 'terlambat',
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);
    }
}

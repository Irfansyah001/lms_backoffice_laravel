<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LibraryBackofficeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_public_register_page_is_not_available(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_login_is_rate_limited_after_too_many_failed_attempts(): void
    {
        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->post(route('login.store'), [
                'email' => 'wrong@example.com',
                'password' => 'password-salah',
            ]);
        }

        $this->post(route('login.store'), [
            'email' => 'wrong@example.com',
            'password' => 'password-salah',
        ])->assertSessionHasErrors('email');
    }

    public function test_admin_can_access_user_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee('Manajemen User');
    }

    public function test_pustakawan_cannot_access_user_management(): void
    {
        $pustakawan = User::factory()->create([
            'role' => 'pustakawan',
            'status' => 'aktif',
        ]);

        $this->actingAs($pustakawan)
            ->get(route('users.index'))
            ->assertForbidden();
    }

    public function test_pustakawan_cannot_access_book_and_category_management(): void
    {
        $pustakawan = User::factory()->create([
            'role' => 'pustakawan',
            'status' => 'aktif',
        ]);

        $this->actingAs($pustakawan)
            ->get(route('books.index'))
            ->assertForbidden();

        $this->actingAs($pustakawan)
            ->get(route('categories.index'))
            ->assertForbidden();
    }

    public function test_pustakawan_dashboard_hides_admin_management_menu(): void
    {
        $pustakawan = User::factory()->create([
            'role' => 'pustakawan',
            'status' => 'aktif',
        ]);

        $this->actingAs($pustakawan)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Manajemen User')
            ->assertDontSee('Manajemen Buku')
            ->assertDontSee('Kategori Buku')
            ->assertDontSee('Kelola buku');
    }

    public function test_pustakawan_can_search_book_availability(): void
    {
        $pustakawan = User::factory()->create([
            'role' => 'pustakawan',
            'status' => 'aktif',
        ]);
        $category = Category::create(['name' => 'Teknologi']);
        Book::create([
            'category_id' => $category->id,
            'title' => 'Panduan Laravel Dasar',
            'author' => 'Tim Kampus',
            'publisher' => 'Kampus Media',
            'publication_year' => 2024,
            'stock' => 4,
            'shelf_location' => 'A1',
            'status' => 'tersedia',
        ]);

        $this->actingAs($pustakawan)
            ->get(route('book-availability.index', ['q' => 'Laravel']))
            ->assertOk()
            ->assertSee('Cek Ketersediaan Buku')
            ->assertSee('Panduan Laravel Dasar')
            ->assertSee('Tersedia')
            ->assertDontSee('Edit')
            ->assertDontSee('Hapus');
    }

    public function test_authenticated_user_can_open_account_settings(): void
    {
        $user = User::factory()->create([
            'role' => 'pustakawan',
            'status' => 'aktif',
        ]);

        $this->actingAs($user)
            ->get(route('account.settings'))
            ->assertOk()
            ->assertSee('Pengaturan Akun')
            ->assertSee($user->email)
            ->assertSee('Ganti Password');
    }

    public function test_user_can_update_own_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'password' => 'password-lama',
            'status' => 'aktif',
        ]);

        $this->actingAs($user)
            ->put(route('account.password.update'), [
                'current_password' => 'password-lama',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect(route('account.settings'));

        $this->assertTrue(Hash::check('password-baru', $user->fresh()->password));
    }

    public function test_user_cannot_update_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => 'password-lama',
            'status' => 'aktif',
        ]);

        $this->actingAs($user)
            ->from(route('account.settings'))
            ->put(route('account.password.update'), [
                'current_password' => 'password-salah',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect(route('account.settings'))
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password-lama', $user->fresh()->password));
    }

    public function test_borrowing_decreases_book_stock(): void
    {
        $user = User::factory()->create(['status' => 'aktif']);
        $category = Category::create(['name' => 'Teknologi']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Belajar Laravel',
            'author' => 'Tim Kampus',
            'stock' => 2,
            'status' => 'tersedia',
        ]);
        $member = Member::create([
            'name' => 'Mahasiswa Demo',
            'status' => 'aktif',
        ]);

        $this->actingAs($user)->post(route('borrowings.store'), [
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
        ])->assertRedirect(route('borrowings.index'));

        $this->assertDatabaseHas('borrowings', [
            'member_id' => $member->id,
            'book_id' => $book->id,
            'status' => 'dipinjam',
        ]);
        $this->assertSame(1, $book->fresh()->stock);
    }

    public function test_zero_stock_book_is_hidden_from_new_borrowing_form(): void
    {
        $user = User::factory()->create(['status' => 'aktif']);
        $category = Category::create(['name' => 'Teknologi']);

        Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Stok Kosong',
            'author' => 'Tim Kampus',
            'stock' => 0,
            'status' => 'tidak_tersedia',
        ]);

        Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Stok Tersedia',
            'author' => 'Tim Kampus',
            'stock' => 2,
            'status' => 'tersedia',
        ]);

        $this->actingAs($user)
            ->get(route('borrowings.create'))
            ->assertOk()
            ->assertDontSee('Buku Stok Kosong')
            ->assertSee('Buku Stok Tersedia');
    }

    public function test_current_borrowed_book_still_appears_when_editing_even_if_stock_is_zero(): void
    {
        $user = User::factory()->create(['status' => 'aktif']);
        $category = Category::create(['name' => 'Teknologi']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Sedang Dipinjam',
            'author' => 'Tim Kampus',
            'stock' => 0,
            'status' => 'tidak_tersedia',
        ]);
        $member = Member::create(['name' => 'Anggota Demo', 'status' => 'aktif']);
        $borrowing = Borrowing::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($user)
            ->get(route('borrowings.edit', $borrowing))
            ->assertOk()
            ->assertSee('Buku Sedang Dipinjam');
    }

    public function test_member_cannot_borrow_same_book_twice_while_active(): void
    {
        $user = User::factory()->create(['status' => 'aktif']);
        $category = Category::create(['name' => 'Teknologi']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Sama',
            'author' => 'Tim Kampus',
            'stock' => 3,
            'status' => 'tersedia',
        ]);
        $member = Member::create(['name' => 'Anggota Demo', 'status' => 'aktif']);

        Borrowing::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($user)->post(route('borrowings.store'), [
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
        ])->assertSessionHasErrors('book_id');
    }

    public function test_member_cannot_have_more_than_three_active_borrowings(): void
    {
        $user = User::factory()->create(['status' => 'aktif']);
        $category = Category::create(['name' => 'Teknologi']);
        $member = Member::create(['name' => 'Anggota Demo', 'status' => 'aktif']);

        $books = collect(range(1, 4))->map(fn (int $number) => Book::create([
            'category_id' => $category->id,
            'title' => "Buku Batas {$number}",
            'author' => 'Tim Kampus',
            'stock' => 2,
            'status' => 'tersedia',
        ]));

        foreach ($books->take(3) as $book) {
            Borrowing::create([
                'member_id' => $member->id,
                'book_id' => $book->id,
                'borrowed_at' => today()->toDateString(),
                'due_date' => today()->addDays(7)->toDateString(),
                'status' => 'dipinjam',
            ]);
        }

        $this->actingAs($user)->post(route('borrowings.store'), [
            'member_id' => $member->id,
            'book_id' => $books->last()->id,
            'borrowed_at' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
        ])->assertSessionHasErrors('member_id');
    }

    public function test_admin_cancel_borrowing_keeps_history_and_restores_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $category = Category::create(['name' => 'Teknologi']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Dibatalkan',
            'author' => 'Tim Kampus',
            'stock' => 1,
            'status' => 'tersedia',
        ]);
        $member = Member::create(['name' => 'Anggota Demo', 'status' => 'aktif']);
        $borrowing = Borrowing::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
        ]);

        $book->decrement('stock');
        $book->refresh()->syncAvailabilityStatus();

        $this->actingAs($admin)
            ->delete(route('borrowings.destroy', $borrowing))
            ->assertRedirect(route('borrowings.index'));

        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'dibatalkan',
        ]);
        $this->assertSame(1, $book->fresh()->stock);
    }

    public function test_pustakawan_cannot_cancel_borrowing(): void
    {
        $pustakawan = User::factory()->create(['role' => 'pustakawan', 'status' => 'aktif']);
        $category = Category::create(['name' => 'Teknologi']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Tidak Bisa Dibatalkan',
            'author' => 'Tim Kampus',
            'stock' => 1,
            'status' => 'tersedia',
        ]);
        $member = Member::create(['name' => 'Anggota Demo', 'status' => 'aktif']);
        $borrowing = Borrowing::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($pustakawan)
            ->delete(route('borrowings.destroy', $borrowing))
            ->assertForbidden();
    }

    public function test_database_seeder_creates_100_dummy_books_and_members(): void
    {
        $this->seed();

        $this->assertSame(100, Book::count());
        $this->assertSame(100, Member::count());
    }

    public function test_member_code_is_generated_automatically(): void
    {
        $member = Member::create([
            'name' => 'Anggota Otomatis',
            'status' => 'aktif',
        ]);

        $this->assertSame('AGT-'.str_pad((string) $member->id, 3, '0', STR_PAD_LEFT), $member->fresh()->member_code);
    }

    public function test_late_return_increases_stock_and_marks_borrowing_as_late(): void
    {
        $user = User::factory()->create(['status' => 'aktif']);
        $category = Category::create(['name' => 'Pendidikan']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Manajemen Perpustakaan',
            'author' => 'Tim Pustaka',
            'stock' => 0,
            'status' => 'tersedia',
        ]);
        $member = Member::create([
            'name' => 'Anggota Demo',
            'status' => 'aktif',
        ]);
        $borrowing = Borrowing::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->subDays(10)->toDateString(),
            'due_date' => today()->subDays(5)->toDateString(),
            'status' => 'dipinjam',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $this->actingAs($user)->put(route('returns.update', $borrowing), [
            'returned_at' => today()->toDateString(),
        ])->assertRedirect(route('returns.index'));

        $this->assertSame(1, $book->fresh()->stock);
        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'status' => 'terlambat',
        ]);
    }

    public function test_return_date_cannot_be_in_the_future(): void
    {
        $user = User::factory()->create(['status' => 'aktif']);
        $category = Category::create(['name' => 'Pendidikan']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Masa Depan',
            'author' => 'Tim Kampus',
            'stock' => 1,
            'status' => 'tersedia',
        ]);
        $member = Member::create(['name' => 'Anggota Demo', 'status' => 'aktif']);
        $borrowing = Borrowing::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($user)->put(route('returns.update', $borrowing), [
            'returned_at' => today()->addDay()->toDateString(),
        ])->assertSessionHasErrors('returned_at');
    }

    public function test_book_status_is_derived_from_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $category = Category::create(['name' => 'Teknologi']);

        $this->actingAs($admin)->post(route('books.store'), [
            'category_id' => $category->id,
            'title' => 'Buku Status Otomatis',
            'author' => 'Tim Kampus',
            'publisher' => 'Kampus Media',
            'publication_year' => 2024,
            'stock' => 0,
            'shelf_location' => 'A1',
        ])->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', [
            'title' => 'Buku Status Otomatis',
            'status' => 'tidak_tersedia',
        ]);
    }

    public function test_detail_pages_are_available(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif']);
        $category = Category::create(['name' => 'Teknologi']);
        $book = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Detail',
            'author' => 'Tim Kampus',
            'stock' => 2,
            'status' => 'tersedia',
        ]);
        $member = Member::create(['name' => 'Anggota Detail', 'status' => 'aktif']);
        $borrowing = Borrowing::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => today()->toDateString(),
            'due_date' => today()->addDays(7)->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($admin)->get(route('books.show', $book))->assertOk()->assertSee('Detail Buku');
        $this->actingAs($admin)->get(route('members.show', $member))->assertOk()->assertSee('Detail Anggota');
        $this->actingAs($admin)->get(route('borrowings.show', $borrowing))->assertOk()->assertSee('Detail Peminjaman');
    }
}

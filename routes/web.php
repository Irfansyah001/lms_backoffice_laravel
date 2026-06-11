<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookAvailabilityController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'active'])->group(function () {
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');

    Route::resource('users', UserController::class)->except(['show'])->middleware('admin');
    Route::resource('racks', RackController::class)->except(['show'])->middleware('admin');
    Route::resource('categories', CategoryController::class)->except(['show'])->middleware('admin');
    Route::resource('books', BookController::class)->middleware('admin');
    Route::get('/book-availability', [BookAvailabilityController::class, 'index'])->name('book-availability.index');
    Route::resource('members', MemberController::class);
    Route::resource('borrowings', BorrowingController::class);

    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{borrowing}/edit', [ReturnController::class, 'edit'])->name('returns.edit');
    Route::put('/returns/{borrowing}', [ReturnController::class, 'update'])->name('returns.update');

});

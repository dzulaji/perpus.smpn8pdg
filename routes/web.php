<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminBooksController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminQuestionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\AdminCalculationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminKriteriaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// user
Route::get('/', [HomeController::class, 'index']);
Route::post('/', [HomeController::class, 'category']);
Route::resource('/books', BookController::class);
Route::resource('/booking', BookingController::class)->middleware('auth');
Route::middleware(['auth'])->group(function () {
    Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.kuisioner');
    Route::post('/rekomendasi/proses', [RekomendasiController::class, 'proses'])->name('rekomendasi.proses');
    Route::get('/rekomendasi/hasil/{id}', [RekomendasiController::class, 'hasil'])->name('rekomendasi.hasil');
    Route::get('/history', [RekomendasiController::class, 'history'])->name('rekomendasi.history');
});

Route::resource('/profile', UserController::class)->middleware('auth');
Route::get('/koleksi', [BookController::class, 'index']);


// admin and librarian
Route::get('/admin', [DashboardController::class, 'index'])->middleware('adminandlibrarian');
Route::resource('/admin/booking', AdminBookingController::class)->middleware('adminandlibrarian');
Route::get('/admin/booking/export/excel', [AdminBookingController::class, 'exportExcel'])->name('admin.booking.export');

// admin only
Route::resource('/admin/books', AdminBooksController::class)->middleware('admin');
Route::post('/admin/books/import', [AdminBooksController::class, 'import'])->middleware('admin')->name('admin.books.import');
Route::resource('/admin/users', UserController::class)->middleware('admin');
Route::put('/admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->middleware('admin')->name('admin.users.reset_password');
//Route::resource('/admin/questions', AdminQuestionController::class)->middleware('admin');
// Route untuk mengelola Kriteria (Create, Read, Update, Delete)
Route::resource('/admin/kriteria', AdminKriteriaController::class)->middleware('admin')->names('admin.kriteria');
// Route untuk mengelola Sub-Kriteria yang berada di bawah Kriteria
Route::post('/admin/kriteria/{kriterium}/subkriteria', [AdminKriteriaController::class, 'storeSubKriteria'])->middleware('admin')->name('admin.subkriteria.store');
Route::put('/admin/subkriteria/{subkriterium}', [AdminKriteriaController::class, 'updateSubKriteria'])->middleware('admin')->name('admin.subkriteria.update');
Route::delete('/admin/subkriteria/{subkriterium}', [AdminKriteriaController::class, 'destroySubKriteria'])->middleware('admin')->name('admin.subkriteria.destroy');
Route::resource('/admin/calculation', AdminCalculationController::class)->middleware('admin');

// login
Route::get('/login', [LoginController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest');

// logout
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

// register
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');

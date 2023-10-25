<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OfficialMemoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and asll of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/proses-register', [RegisterController::class, 'registerProcess'])->name('proses.register');

    // User Management
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user-edit/{user:id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/proses-edit-user', [UserController::class, 'editProcess'])->name('edit.user.proses');
    Route::get('/delete-user/{id}', [UserController::class, 'delete'])->name('delete.user.proses');
    Route::get('/ganti-password/{user:id}', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::post('/proses-ganti-password/{user:id}', [UserController::class, 'changePasswordProcess'])->name('user.change-password.process');

    // Tambah Nota Dinas    
    Route::get('/tambah-nota-dinas', [OfficialMemoController::class, 'showCreatePage'])->name('officialmemo.create.show');
    Route::post('/proses-tambah-nota-dinas', [OfficialMemoController::class, 'create'])->name('officialmemo.create.process');

    // Edit Nota Dinas
    Route::get('/nota-dinas/ubah/{officialMemo:id}', [OfficialMemoController::class, 'showEditPage'])->name('officialmemo.edit.show');
    Route::post('/proses-ubah-nota-dinas', [OfficialMemoController::class, 'edit'])->name('officialmemo.edit.process');

    Route::post('/nota-dinas/penomoran', [OfficialMemoController::class, 'officialMemoNumberingLive'])->name('officialmemo.numbering');

    // Hapus Nota Dinas
    Route::get('/nota-dinas/hapus/{id}', [OfficialMemoController::class, 'delete'])->name('officialmemo.delete');

    // Tambah Berita Acara    
    Route::get('/tambah-berita-acara', [NewsController::class, 'showCreatePage'])->name('news.create.show');
    Route::post('/proses-tambah-berita-acara', [NewsController::class, 'create'])->name('news.create.process');

    // Edit Berita Acara
    Route::get('/berita-acara/ubah/{news:id}', [NewsController::class, 'showEditPage'])->name('news.edit.show');
    Route::post('/proses-ubah-berita-acara', [NewsController::class, 'edit'])->name('news.edit.process');

    Route::post('/berita-acara/penomoran', [NewsController::class, 'newsNumberingLive'])->name('news.numbering');

    // Hapus Berita Acara
    Route::get('/berita-acara/hapus/{id}', [NewsController::class, 'delete'])->name('news.delete');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Nota dinas
    Route::get('/nota-dinas', [OfficialMemoController::class, 'index'])->name('officialmemo');
    Route::get('/nota-dinas/{officialMemo:id}', [OfficialMemoController::class, 'showDetailPage'])->name('officialmemo.detail.show');
    Route::post('/nota-dinas/search', [OfficialMemoController::class, 'search'])->name('officialmemo.search');

    // Berita Acara
    Route::get('/berita-acara', [NewsController::class, 'index'])->name('news');
    Route::get('/berita-acara/{news:id}', [NewsController::class, 'showDetailPage'])->name('news.detail.show');
    Route::post('/berita-acara/search', [NewsController::class, 'search'])->name('news.search');

    // Logout
    Route::post('/logout', [HomeController::class, 'logout']);
});

Auth::routes(['verify' => false, 'register' => false, 'reset' => false]);

<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfficialMemoController;
use App\Http\Controllers\UserController;
use App\Models\OfficialMemo;
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
    Route::post('/proses-tambah-nota-dinas', [OfficialMemoController::class, 'createProcess'])->name('officialmemo.create.process');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Nota dinas
    Route::get('/nota-dinas', [OfficialMemoController::class, 'index'])->name('officialmemo');

    // Logout
    Route::post('/logout', [HomeController::class, 'logout']);
});

Auth::routes(['verify' => false, 'register' => false, 'reset' => false]);

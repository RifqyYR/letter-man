<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentAuthorizationLetterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

    // Hapus Nota Dinas
    // Route::get('/nota-dinas/hapus/{id}', [OfficialMemoController::class, 'delete'])->name('officialmemo.delete');

    // Hapus Berita Acara
    // Route::get('/berita-acara/hapus/{id}', [NewsController::class, 'delete'])->name('news.delete');

    // Hapus Surat Keluar
    // Route::get('/surat-keluar/hapus/{id}', [OutgoingMailController::class, 'delete'])->name('outgoingmail.delete');

    // Hapus Kebenaran Dokumen
    Route::get('/kebenaran-dokumen/hapus/{id}', [DocumentAuthorizationLetterController::class, 'delete'])->name('documentauthorizationletter.delete');

    // Hapus Arsip
    Route::get('/arsip/hapus/{id}', [ArchiveController::class, 'delete'])->name('archive.delete');

    Route::post('/rekap-kd', [HomeController::class, 'recapDocumentAuthorizationLetter']);
    // Route::post('/rekap-nota-dinas', [HomeController::class, 'recapOfficialMemo']);
    // Route::post('/rekap-berita-acara', [HomeController::class, 'recapNews']);
    // Route::post('/rekap-surat-keluar', [HomeController::class, 'recapOutgoingMail']);
    Route::post('/rekap-arsip', [HomeController::class, 'recapArchive']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Nota dinas
    // Route::get('/nota-dinas', [OfficialMemoController::class, 'index'])->name('officialmemo');
    // Route::get('/nota-dinas/{officialMemo:id}', [OfficialMemoController::class, 'showDetailPage'])->name('officialmemo.detail.show');
    // Route::post('/nota-dinas/search', [OfficialMemoController::class, 'search'])->name('officialmemo.search');

    // Tambah Nota Dinas    
    // Route::get('/tambah-nota-dinas', [OfficialMemoController::class, 'showCreatePage'])->name('officialmemo.create.show');
    // Route::post('/proses-tambah-nota-dinas', [OfficialMemoController::class, 'create'])->name('officialmemo.create.process');

    // Edit Nota Dinas
    // Route::get('/nota-dinas/ubah/{officialMemo:id}', [OfficialMemoController::class, 'showEditPage'])->name('officialmemo.edit.show');
    // Route::post('/proses-ubah-nota-dinas', [OfficialMemoController::class, 'edit'])->name('officialmemo.edit.process');
    // Route::post('/nota-dinas/penomoran', [OfficialMemoController::class, 'officialMemoNumberingLive'])->name('officialmemo.numbering');

    // Berita Acara
    // Route::get('/berita-acara', [NewsController::class, 'index'])->name('news');
    // Route::get('/berita-acara/{news:id}', [NewsController::class, 'showDetailPage'])->name('news.detail.show');
    // Route::post('/berita-acara/search', [NewsController::class, 'search'])->name('news.search');

    // Tambah Berita Acara    
    // Route::get('/tambah-berita-acara', [NewsController::class, 'showCreatePage'])->name('news.create.show');
    // Route::post('/proses-tambah-berita-acara', [NewsController::class, 'create'])->name('news.create.process');

    // Edit Berita Acara
    // Route::get('/berita-acara/ubah/{news:id}', [NewsController::class, 'showEditPage'])->name('news.edit.show');
    // Route::post('/proses-ubah-berita-acara', [NewsController::class, 'edit'])->name('news.edit.process');
    // Route::post('/berita-acara/penomoran', [NewsController::class, 'newsNumberingLive'])->name('news.numbering');

    // Kebenaran Dokumen
    Route::get('/kebenaran-dokumen', [DocumentAuthorizationLetterController::class, 'index'])->name('documentauthorizationletter');
    Route::get('/kebenaran-dokumen/{documentAuthorizationLetter:id}', [DocumentAuthorizationLetterController::class, 'showDetailPage'])->name('documentauthorizationletter.detail.show');
    Route::post('/kebenaran-dokumen/search', [DocumentAuthorizationLetterController::class, 'search'])->name('documentauthorizationletter.search');
    Route::get('/kebenaran-dokumen/dokumen/{documentAuthorizationLetter:id}', [DocumentAuthorizationLetterController::class, 'firstPage'])->name('documentauthorizationletter.download.first-page');
    Route::get('/kebenaran-dokumen/cetak/{documentAuthorizationLetter:id}', [DocumentAuthorizationLetterController::class, 'print'])->name('documentauthorizationletter.print');

    // Arsip
    Route::get('/arsip', [ArchiveController::class, 'index'])->name('archive');
    Route::get('/arsip/{archive:id}', [ArchiveController::class, 'showDetailPage'])->name('archive.detail.show');
    Route::post('/arsip/search', [ArchiveController::class, 'search'])->name('archive.search');

    // Tambah Arsip 
    Route::get('/tambah-arsip', [ArchiveController::class, 'showCreatePage'])->name('archive.create.show');
    Route::post('/proses-tambah-arsip', [ArchiveController::class, 'create'])->name('archive.create.process');

    // Edit Arsip
    Route::get('/arsip/ubah/{archive:id}', [ArchiveController::class, 'showEditPage'])->name('archive.edit.show');
    Route::post('/proses-ubah-arsip', [ArchiveController::class, 'edit'])->name('archive.edit.process');
    Route::post('/arsip/penomoran', [ArchiveController::class, 'newsNumberingLive'])->name('archive.numbering');

    // Tambah Surat Kebenaran Dokumen    
    Route::get('/tambah-kebenaran-dokumen', [DocumentAuthorizationLetterController::class, 'showCreatePage'])->name('documentauthorizationletter.create.show');
    Route::post('/proses-tambah-kebenaran-dokumen', [DocumentAuthorizationLetterController::class, 'create'])->name('documentauthorizationletter.create.process');
    Route::post('/kebenaran-dokumen/penomoran', [DocumentAuthorizationLetterController::class, 'documentAuthorizationLetterNumberingLive'])->name('documentauthorizationletter.numbering');
    Route::post('/upload-kd', [DocumentAuthorizationLetterController::class, 'uploads']);
    Route::post('/tambah-kebenaran-dokumen/vendor', [DocumentAuthorizationLetterController::class, 'selectVendor'])->name('documentauthorizationletter.vendor');

    // Edit Kebenaran Dokumen
    Route::get('/kebenaran-dokumen/ubah/{documentAuthorizationLetter:id}', [DocumentAuthorizationLetterController::class, 'showEditPage'])->name('documentauthorizationletter.edit.show');
    Route::post('/proses-ubah-kebenaran-dokumen', [DocumentAuthorizationLetterController::class, 'edit'])->name('documentauthorizationletter.edit.process');
    Route::post('/ubah-kebenaran-dokumen/vendor', [DocumentAuthorizationLetterController::class, 'selectVendor'])->name('documentauthorizationletter.edit.vendor');

    // Surat Keluar
    // Route::get('/surat-keluar', [OutgoingMailController::class, 'index'])->name('outgoingmail');
    // Route::get('/surat-keluar/{outgoingmail:id}', [OutgoingMailController::class, 'showDetailPage'])->name('outgoingmail.detail.show');
    // Route::post('/surat-keluar/search', [OutgoingMailController::class, 'search'])->name('outgoingmail.search');

    // Tambah Surat Keluar    
    // Route::get('/tambah-surat-keluar', [OutgoingMailController::class, 'showCreatePage'])->name('outgoingmail.create.show');
    // Route::post('/proses-tambah-surat-keluar', [OutgoingMailController::class, 'create'])->name('outgoingmail.create.process');

    // Edit Surat Keluar
    // Route::get('/surat-keluar/ubah/{outgoingmail:id}', [OutgoingMailController::class, 'showEditPage'])->name('outgoingmail.edit.show');
    // Route::post('/proses-ubah-surat-keluar', [OutgoingMailController::class, 'edit'])->name('outgoingmail.edit.process');
    // Route::post('/surat-keluar/penomoran', [OutgoingMailController::class, 'outgoingMailNumberingLive'])->name('outgoingmail.numbering');

    // Logout
    Route::post('/logout', [HomeController::class, 'logout']);

    Route::delete('/upload-kd/kebenaran-dokuman/delete-tmp', [DocumentAuthorizationLetterController::class, 'deleteTmp']);
});

Auth::routes(['verify' => false, 'register' => false, 'reset' => false]);

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;


Route::redirect('/', '/login');

Route::fallback(fn () => redirect('/login'));

/*
|--------------------------------------------------------------------------
| Dashboard (terproteksi)
|--------------------------------------------------------------------------
| Setelah login & verifikasi email, user diarahkan ke sini.
| Admin → panel 'admin', selain admin → panel 'user'.
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    /** @var \App\Models\User|null $user */
    if ($user && $user->hasRole('admin')) {
        return redirect(Filament::getPanel('admin')->getUrl());
    }

    return redirect(Filament::getPanel('user')->getUrl());
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profil (terproteksi)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dokumen/download/{id}', [App\Http\Controllers\DocumentController::class, 'download'])->name('dokumen.download');
    Route::get('/dokumen/view/{id}', [App\Http\Controllers\DocumentController::class, 'view'])->name('dokumen.view');
    Route::get('/dokumen/{id}', [App\Http\Controllers\DocumentController::class, 'show'])->name('dokumen.show');

    // Template Download Routes
    Route::get('/template/arsip-unit/excel', [App\Actions\TemplateExportAction::class, 'arsipUnitExcel'])->name('template.arsip-unit.excel');
    Route::get('/template/arsip-unit/pdf', [App\Actions\TemplateExportAction::class, 'arsipUnitPdf'])->name('template.arsip-unit.pdf');
    Route::get('/template/daftar-isi-berkas/excel', [App\Actions\TemplateExportAction::class, 'daftarIsiBerkasExcel'])->name('template.daftar-isi-berkas.excel');
    Route::get('/template/daftar-isi-berkas/pdf', [App\Actions\TemplateExportAction::class, 'daftarIsiBerkasPdf'])->name('template.daftar-isi-berkas.pdf');
    Route::get('/template/import-arsip-unit/excel', [App\Actions\TemplateExportAction::class, 'importArsipUnitExcel'])->name('template.import-arsip-unit.excel');
});


/*
|--------------------------------------------------------------------------
| Auth scaffolding routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

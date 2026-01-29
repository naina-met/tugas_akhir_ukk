<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// DASHBOARD (REVISI: verified DIHAPUS)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);

    Route::get('/export-items', [App\Http\Controllers\ExportController::class, 'exportItems'])
        ->name('export.items');

    Route::resource('stock-ins', StockInController::class);

    Route::get('/export-stock-ins', [App\Http\Controllers\ExportController::class, 'exportStockIns'])
        ->name('export.stockins');

    Route::resource('stock-outs', StockOutController::class);

    Route::get('/export-stock-outs', [App\Http\Controllers\ExportController::class, 'exportStockOuts'])
        ->name('export.stockouts');

    Route::get('/export-damage-reports', [App\Http\Controllers\ExportController::class, 'exportDamageReports'])
        ->name('export.damagereports');
        
    // === DAMAGE REPORTS ROUTES ===
    Route::get('/lapor-kerusakan', [\App\Http\Controllers\DamageReportController::class, 'create'])
        ->name('damage-reports.create');

    Route::post('/lapor-kerusakan', [\App\Http\Controllers\DamageReportController::class, 'store'])
        ->name('damage-reports.store');

    Route::get('/admin/laporan-kerusakan', [\App\Http\Controllers\DamageReportController::class, 'index'])
        ->name('damage-reports.admin');

    Route::post('/admin/laporan-kerusakan/store', [\App\Http\Controllers\DamageReportController::class, 'storeFromAdmin'])
        ->name('damage-reports.admin.store');

    Route::put('/admin/laporan-kerusakan/{id}', [\App\Http\Controllers\DamageReportController::class, 'update'])
        ->name('damage-reports.update');

    Route::delete('/admin/laporan-kerusakan/{id}', [\App\Http\Controllers\DamageReportController::class, 'destroy'])
        ->name('damage-reports.destroy');

    Route::post('/admin/laporan-kerusakan/{id}/process', [\App\Http\Controllers\DamageReportController::class, 'process'])
        ->name('damage-reports.process');

    Route::post('/admin/laporan-kerusakan/{id}/done', [\App\Http\Controllers\DamageReportController::class, 'done'])
        ->name('damage-reports.done');
});



Route::get('/argon', function () {
    return view('layouts.argon');
})->middleware(['auth']);

Route::middleware(['auth', 'can:manage-users'])->group(function () {
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';

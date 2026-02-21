<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    CategoryController,
    ItemController,
    StockInController,
    StockOutController,
    UserController,
    DashboardController,
    DamageReportController,
    ExportController,
    ReportController,
    RekapDashboardController,
    ActivityLogController
};

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ===== DASHBOARD REKAP =====
    Route::get('/dashboard-rekap', [RekapDashboardController::class, 'index'])
        ->name('dashboard.rekap');
});

/*
|--------------------------------------------------------------------------
| AUTH AREA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ===== PROFILE =====
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // ===== MASTER DATA =====
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);

    // ===== STOCK IN =====
    Route::resource('stock-ins', StockInController::class);

    Route::get('/export-stock-ins', [ExportController::class, 'exportStockIns'])
        ->name('export.stockins');

    // ===== STOCK OUT =====
    // View-only routes for superadmin
    Route::get('stock-outs', [StockOutController::class, 'index'])->name('stock-outs.index');
    Route::get('stock-outs/{stockOut}', [StockOutController::class, 'show'])->name('stock-outs.show');
    
    // Restrict superadmin from managing stock out
    Route::middleware('can:manage-stock-out')->group(function () {
        Route::get('stock-outs/create', [StockOutController::class, 'create'])->name('stock-outs.create');
        Route::post('stock-outs', [StockOutController::class, 'store'])->name('stock-outs.store');
        Route::get('stock-outs/{stockOut}/edit', [StockOutController::class, 'edit'])->name('stock-outs.edit');
        Route::put('stock-outs/{stockOut}', [StockOutController::class, 'update'])->name('stock-outs.update');
        Route::delete('stock-outs/{stockOut}', [StockOutController::class, 'destroy'])->name('stock-outs.destroy');
    });

    Route::get('/export-stock-outs', [ExportController::class, 'exportStockOuts'])
        ->name('export.stockouts');

    // ===== EXPORT ITEMS =====
    Route::get('/export-items', [ExportController::class, 'exportItems'])
        ->name('export.items');

    // ===== DAMAGE REPORTS (USER) =====
    Route::get('/lapor-kerusakan', [DamageReportController::class, 'create'])
        ->name('damage-reports.create');

    Route::post('/lapor-kerusakan', [DamageReportController::class, 'store'])
        ->name('damage-reports.store');

    // ===== DAMAGE REPORTS (ADMIN) =====
    Route::get('/admin/laporan-kerusakan', [DamageReportController::class, 'index'])
        ->name('damage-reports.admin');

    Route::post('/admin/laporan-kerusakan/store', [DamageReportController::class, 'storeFromAdmin'])
        ->name('damage-reports.admin.store');

    Route::put('/admin/laporan-kerusakan/{id}', [DamageReportController::class, 'update'])
        ->name('damage-reports.update');

    Route::delete('/admin/laporan-kerusakan/{id}', [DamageReportController::class, 'destroy'])
        ->name('damage-reports.destroy');

    Route::post('/admin/laporan-kerusakan/{id}/process', [DamageReportController::class, 'process'])
        ->name('damage-reports.process');

    Route::post('/admin/laporan-kerusakan/{id}/done', [DamageReportController::class, 'done'])
        ->name('damage-reports.done');

    Route::get('/export-damage-reports', [ExportController::class, 'exportDamageReports'])
        ->name('export.damagereports');

    // ===== LAPORAN BARANG =====
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    // ===== ACTIVITY LOGS (SUPERADMIN ONLY) =====
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index')
        ->middleware('role:superadmin');
});

/*
|--------------------------------------------------------------------------
| ADMIN REGISTRATION
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/admin/register', [UserController::class, 'showAdminRegister'])
        ->name('admin.register.form');
    Route::post('/admin/register', [UserController::class, 'adminRegister'])
        ->name('admin.register');
});

/*
|--------------------------------------------------------------------------
| ADMIN USERS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:manage-users'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])
        ->name('users.approve');
    Route::post('/users/{user}/reject', [UserController::class, 'reject'])
        ->name('users.reject');
});

/*
|--------------------------------------------------------------------------
| TEST VIEW
|--------------------------------------------------------------------------
*/
Route::get('/argon', function () {
    return view('layouts.argon');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

Route::get('/get-kelompok/{jenis}', function($jenis){
    return \App\Models\KelompokBarang::where('jenis_barang_id',$jenis)->get();
});

Route::get('/get-category/{kelompok}', function($kelompok){
    return \App\Models\Category::where('kelompok_barang_id',$kelompok)->get();
});

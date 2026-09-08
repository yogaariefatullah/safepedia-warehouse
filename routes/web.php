<?php

use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseRequestController;
use App\Http\Controllers\warehouseDocumentController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\UsermanagentController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', '2fa'])->name('dashboard');


Route::middleware(['auth', '2fa'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | warehouse-requests
    |--------------------------------------------------------------------------
    */
    Route::resource('warehouse-requests', WarehouseRequestController::class);
    Route::post('/warehouse-requests/{warehouseRequest}/documents', [WarehouseRequestController::class, 'uploadDocument'])->name('warehouse-requests.documents.upload');
    Route::post('/warehouse-requests/{warehouseRequest}/submit', [WarehouseRequestController::class, 'submit'])->name('warehouse-requests.submit');
    Route::get('/warehouse-requests/documents/{document}/download', [WarehouseDocumentController::class, 'download'])->name('warehouse-requests.documents.download');
    Route::delete(
        '/warehouse-requests/documents/{document}/destroy',
        [WarehouseRequestController::class, 'destroyDocument']
    )->name('warehouse-requests.documents.destroy.zz');
});

Route::middleware(['auth', '2fa', 'role:spv_gudang,kepala_gudang,manager_operasional,direktur_operasional,direktur_keuangan',])->prefix('approvals')->name('approvals.')->group(function () {
    Route::get('/', [ApprovalController::class, 'index'])->name('index');
    Route::get('/{warehouseRequest}', [ApprovalController::class, 'show'])->name('show');
    Route::post('/{warehouseRequest}/approve', [ApprovalController::class, 'approve'])->name('approve');
    Route::post('/{warehouseRequest}/reject', [ApprovalController::class, 'reject'])->name('reject');
});
Route::middleware(['auth', '2fa', 'role:admin'])->prefix('usermanagement')->name('usermanagement.')->group(function () {
    Route::get('/', [UsermanagentController::class, 'index'])->name('index');

    Route::get('/{user}/edit', [UsermanagentController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UsermanagentController::class, 'update'])->name('update');
});

/*
|--------------------------------------------------------------------------
| 2fa
|--------------------------------------------------------------------------
*/

Route::get('/2fa/setup', [TwoFactorController::class, 'show'])
    ->name('2fa.setup');

Route::post('/2fa/generate', [TwoFactorController::class, 'generate'])
    ->name('2fa.generate');

Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])
    ->name('2fa.enable');

Route::get('/2fa/challenge', [TwoFactorController::class, 'challenge'])
    ->name('2fa.challenge');

Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])
    ->name('2fa.verify');

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseRequestController;
use App\Http\Controllers\warehouseDocumentController;

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
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | warehouse-requests
    |--------------------------------------------------------------------------
    */

    Route::resource('warehouse-requests', WarehouseRequestController::class);
    Route::post('/warehouse-requests/{warehouseRequest}/documents', [WarehouseRequestController::class, 'uploadDocument'])->name('warehouse-requests.documents.upload');
    Route::post('/warehouse-requests/{warehouseRequest}/submit', [WarehouseRequestController::class, 'submit'])->name('warehouse-requests.submit');
    Route::get(
        '/warehouse-requests/documents/{document}/download',
        [WarehouseDocumentController::class, 'download']
    )->name('warehouse-requests.documents.download');
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

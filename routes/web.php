<?php

use App\Http\Controllers\WithdrawalController;
use App\Http\Requests\WithdrawalRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['verified'])->name('dashboard');

Route::middleware(['verified'])->group(function () {
    Route::post('/withdraw', [WithdrawalController::class, 'store'])->name('withdrawal.store');
    Route::view('/withdraw', 'withdraw')->name('withdrawal.show');
});


require __DIR__.'/auth.php';

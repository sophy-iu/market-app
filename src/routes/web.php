<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;

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

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'detail']);

Route::middleware('auth','verified')->group(function () {
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase']);
    
    Route::get('/sell', [ItemController::class, 'sell']);

    Route::get('/purchase/address/{item_id}', [AddressController::class, 'address']);
    Route::post('/purchase/address/{item_id}', [AddressController::class, 'update']);

    Route::get('/mypage', [ProfileController::class, 'profile']);
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);

    Route::post('/item/{item_id}', [ItemController::class, 'like']);
    Route::post('/item/{item_id}', [ItemController::class, 'comment']);
});
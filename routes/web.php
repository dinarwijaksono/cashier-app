<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;


// HomeController
Route::get('/', [HomeController::class, 'index']);
// end HomeController

// ItemController
Route::get('/master-item', [ItemController::class, 'index']);

Route::get('/add-item', [ItemController::class, 'addItem']);

Route::get("/edit-item/{code}", [ItemController::class, 'editItem']);

Route::get('/add-stock/{code}', [ItemController::class, 'addStock']);
// end ItemController

// ReceipController
Route::get('/receipt-history', [ReceiptController::class, 'index']);
// end ReceipController

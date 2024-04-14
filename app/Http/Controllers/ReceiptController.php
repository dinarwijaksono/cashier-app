<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReceiptController extends Controller
{
    public function boot()
    {
        Log::withContext(['class' => ReceiptController::class]);
    }

    public function index()
    {
        self::boot();

        Log::info('get /receipt-history success');

        return view('receipt.index');
    }
}

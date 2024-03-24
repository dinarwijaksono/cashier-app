<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function boot()
    {
        Log::withContext(['class' => ItemController::class]);
    }

    public function index()
    {
        self::boot();

        Log::info('get /master-item success');

        return view('item.index');
    }


    public function addItem()
    {
        self::boot();

        Log::info('get /add-item success');

        return view('item.addItem');
    }
}

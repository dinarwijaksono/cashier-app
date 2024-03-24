<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function boot()
    {
        Log::withContext(['class' => HomeController::class]);
    }

    public function index()
    {
        self::boot();

        Log::info('get / success');

        return view('home.index');
    }
}

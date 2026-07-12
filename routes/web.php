<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(BookController::class)->group(function() {
    Route::get('/book/list', 'list');
});

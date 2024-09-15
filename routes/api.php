<?php

use App\Http\Controllers\ArticlesController;
use Illuminate\Support\Facades\Route;

Route::get('/articles', [ArticlesController::class, 'index']);
Route::post('/articles/store', [ArticlesController::class, 'store']);
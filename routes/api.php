<?php

use App\Http\Controllers\ArticlesController;
use Illuminate\Support\Facades\Route;

// List all articles
Route::get('/articles', [ArticlesController::class, 'index']);

//Search by criteria
Route::get('/articles/search', [ArticlesController::class, 'search']);

// Show a specific article
Route::get('/articles/{id}', [ArticlesController::class, 'show']);

// Store a new article
Route::post('/articles', [ArticlesController::class, 'store']);


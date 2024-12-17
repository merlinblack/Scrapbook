<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SiteMapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArticleController::class, 'index']);
Route::get('article/{slug}', [ArticleController::class, 'show']);
Route::get('sitemap.xml', [SiteMapController::class, 'index']);

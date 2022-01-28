<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SiteMapController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [ArticleController::class, 'index']);
Route::get('article/{slug}', [ArticleController::class, 'show']);
Route::get('sitemap.xml', [SiteMapController::class, 'index']);

/*
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
*/

//require __DIR__.'/auth.php';

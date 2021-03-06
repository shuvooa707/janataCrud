<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', [HomeController::class, 'show']);

Route::post('/create', [HomeController::class, 'create']);
Route::post('/remove', [HomeController::class, 'destroy']);
Route::post('/update', [HomeController::class, 'update']);

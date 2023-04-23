<?php

use App\Http\Controllers\HistoricalData;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'history'], function ($router) {
    Route::get('/', function () {
    return view('history');
    });
    Route::post('/', [HistoricalData::class, 'show'])->name('history.show');
});

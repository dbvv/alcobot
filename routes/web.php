<?php

use App\Http\Controllers\BotController;
use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'tg'], function () {
    Route::get('set', [BotController::class, 'set'])->middleware('auth')->name('tg.set');
    Route::get('webhook', [BotController::class, 'webhook'])->name('tg.webhook');
    Route::get('updates', [BotController::class, 'updates'])->name('tg.updates');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

<?php

header("Access-Control-Allow-Origin: *");
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaceController;
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

Route::get('/place',[PlaceController::class,'searchPlace'] )->name('showPlace');
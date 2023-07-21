<?php

use App\Http\Controllers\SimulationController;
use App\Http\Controllers\StandingsController;
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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', [SimulationController::class, 'simulate']);

Route::get('/standings/{week}', [StandingsController::class, 'show']);
Route::post('/play-week/{week}', [StandingsController::class, 'playWeek']);
Route::post('/play-all-matches', [StandingsController::class, 'playAllWeek']);




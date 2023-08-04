<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PredictionController;
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
Route::get('/', [HomeController::class, 'index']);

Route::get('/standings/{week}', [StandingsController::class, 'show']);
Route::get('/week-predictions/{week}', [PredictionController::class, 'show']);
Route::get('/week-results/{week}', [MatchController::class, 'show']);
Route::post('/play-week/{week}', [MatchController::class, 'playWeek']);
Route::post('/play-all-matches', [MatchController::class, 'playAllWeek']);
Route::post('/reset-league', [SimulationController::class, 'resetLeague']);




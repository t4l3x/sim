<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\LeagueController;
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

Route::get('/standings/{league}', [StandingsController::class, 'show']);
Route::get('/week-predictions/{league}/{week}', [PredictionController::class, 'show']);
Route::get('/week-results/{league}/{week}', [MatchController::class, 'show']);
Route::post('/play-week/{league}/{week}', [MatchController::class, 'playWeek']);
Route::post('/play-all-matches/{league}', [MatchController::class, 'playAllWeek']);
Route::get('/total-weeks/{league}', [MatchController::class, 'totalWeeks']);
Route::post('/reset-league/{league}', [LeagueController::class, 'resetLeague']);
Route::post('/update-result/{matchId}', [MatchController::class, 'updateResult']);



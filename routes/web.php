<?php

use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\TokenController;
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

Route::get('/login', function () {
    return "Login page";
})->name('login');

Route::post('/tokens/create', [TokenController::class, 'create']);

//SECTION - users
Route::group(['prefix'=> 'users', 'middleware'=> ['auth:sanctum']], function () {
    // user achievements
    Route::get('{user}/achievements', [AchievementsController::class, 'index']);
});

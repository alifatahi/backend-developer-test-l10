<?php

use App\Http\Controllers\AchievementsController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/tokens/create', function () {
    // create a sample token for first user
    $user = Auth::loginUsingId(1);
    $token = $user->createToken(request()->token_name);
    return ['token' => $token->plainTextToken];
});

//SECTION - users
Route::group(['prefix'=> 'users', 'middleware'=> ['auth:sanctum']], function () {
    // user achievements
    Route::get('{user}/achievements', [AchievementsController::class, 'index']);

});

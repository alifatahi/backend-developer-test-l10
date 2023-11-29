<?php

use App\Events\CommentWritten;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\TokenController;
use App\Listeners\CheckCommentWriteAchievement;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Comment;
use App\Models\User;
use Database\Factories\LessonFactory;
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

Route::get('/login', function () {
    return "Login page";
})->name('login');

Route::post('/tokens/create', [TokenController::class, 'create']);

//SECTION - users
Route::group(['prefix'=> 'users', 'middleware'=> ['auth:sanctum']], function () {
    // user achievements
    Route::get('{user}/achievements', [AchievementsController::class, 'index']);
});

Route::get('u/{user}', [AchievementsController::class, 'index']);

Route::get('/test', function(){
    $user = Auth::loginUsingId(1);

    dd($user->lastAchievements('lesson-watch')->get()->toArray());

    DB::statement('truncate table comments');
    DB::statement('truncate table lesson_user');
    DB::statement('truncate table achievement_user');

    Comment::factory()->create([
        'user_id' => $user->id,
    ]);
    Comment::factory()->create([
        'user_id' => $user->id,
    ]);
    $comment = Comment::factory()->create([
        'user_id' => $user->id,
    ]);

    event(new CommentWritten($user, $comment));
});

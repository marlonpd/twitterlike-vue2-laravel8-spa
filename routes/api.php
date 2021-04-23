<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\TweetController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\FollowController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('loginfailed', function () {
    return response()->json(['error' => 'unauthenticated']);
})->name('login.failed');

Route::middleware('api')->group(function () {
  
    Route::post('/authenticate', [AuthController::class, 'authenticate', 'as' => 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register/confirm-pin', [AuthController::class, 'confirmPin'])->name('confirm.pin');

    Route::group([
        'middleware' => 'auth:api',
    ], function ($router) {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh/token', [AuthController::class, 'refresh'])->name('refresh');
        Route::put('/update/user', [UserController::class, 'updateUser'])->name('update.user');

        Route::get('tweets/{pageIndex}', [TweetController::class, 'index'])->name('tweets');
        Route::post('tweet/store', [TweetController::class, 'store'])->name('store.tweet');
        Route::put('tweet/update', [TweetController::class, 'update'])->name('update.tweet');
        Route::delete('tweet/delete', [TweetController::class, 'destroy'])->name('delete.tweet');
        Route::get('/tweets/search/{searchKey}', [TweetController::class, 'search'])->name('search.tweets');
        Route::get('api/tweet/{id}', [TweetController::class, 'show'])->name('tweet');

        Route::get('tweet/{id}/comments', [CommentController::class, 'index'])->name('tweet.comments');
        Route::post('comment/store', [CommentController::class, 'store'])->name('store.comment');
        Route::put('comment/update', [CommentController::class, 'update'])->name('update.comment');
        Route::delete('comment/delete', [CommentController::class, 'delete'])->name('delete.comment');
        
        Route::post('follow-unfollow', [CommentController::class, 'followUnfollow'])->name('follow.unfollow');
        Route::get('followers/{userId}', [CommentController::class, 'getFollowers'])->name('get.followers');
        Route::get('remove-follower/{followerId}', [CommentController::class, 'removeFollower'])->name('remove.follower');
        Route::get('following/{userId}', [CommentController::class, 'getFollowing'])->name('get.following');
    });

});

// Route::post('/authenticate', [AuthController::class, 'authenticate', 'as' => 'login'])->name('login');
// Route::post('/register', [AuthController::class, 'register'])->name('register');
// Route::post('/register/confirm-pin', [AuthController::class, 'confirmPin'])->name('confirm.pin');
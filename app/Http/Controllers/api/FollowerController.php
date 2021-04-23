<?php

namespace App\Http\Controllers\api;

use App\Repositories\FollowerRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;

class FollowerController extends Controller
{
    // Route::post('follow-unfollow', [CommentController::class, 'followUnfollow'])->name('follow.unfollow');
    // Route::get('followers/{userId}', [CommentController::class, 'getFollowers'])->name('get.followers');
    // Route::get('remove-follower/{followerId}', [CommentController::class, 'removeFollower'])->name('remove.follower');
    // Route::get('following/{userId}', [CommentController::class, 'getFollowing'])->name('get.following');

    private $followerRepository;

    public function __construct(FollowerRepositoryInterface $followerRepository)
    {
        $this->followerRepository = $followerRepository;
    }

    public function followUnfollow(int $followingId)
    {
        $userId = JWTAuth::user()->id;
        $this->followerRepository->followUnfollow($userId, $followingId);
    }

    public function removeFollower(int $followerId)
    {
        $userId = JWTAuth::user()->id;
        $this->followerRepository->followUnfollow($userId, $followingId);
    }

    public function getFollowers(int $userId)
    {
        $followers = $this->followerRepository->getFollowers($userId);

        return response()->json(['followers' => $followers]);
    }

    public function getFollowing(int $userId)
    {
        $following = $this->followerRepository->getFollowing($userId);

        return response()->json(['following' => $following]);
    }
}
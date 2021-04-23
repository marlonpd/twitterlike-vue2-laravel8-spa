<?php

namespace App\Providers;

use App\Repositories\EloquentRepositoryInterface; 

use App\Repositories\Eloquent\BaseRepository; 
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface; 
use App\Repositories\Eloquent\UserRepository; 
use App\Repositories\TweetRepositoryInterface; 
use App\Repositories\Eloquent\TweetRepository; 
use App\Repositories\CommentRepositoryInterface; 
use App\Repositories\Eloquent\CommentRepository; 
use App\Repositories\FollowerRepositoryInterface; 
use App\Repositories\Eloquent\FollowerRepository; 

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TweetRepositoryInterface::class, TweetRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(FollowerRepositoryInterface::class, FollowerRepository::class);  
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
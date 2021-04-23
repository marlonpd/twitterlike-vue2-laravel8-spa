<?php

namespace App\Repositories\Eloquent;

use App\Models\Follower;
use App\Repositories\FollowerRepositoryInterface;
use Illuminate\Support\Collection;

class FollowerRepository extends BaseRepository implements FollowerRepositoryInterface
{

   /**
    * UserRepository constructor.
    *
    * @param User $model
    */
   public function __construct(Follower $model)
   {
       parent::__construct($model);
   }

   /**
    * @return Collection
    */
   public function all(): Collection
   {
       return $this->model->all();    
   }

   public function followUnfollow(int $userId, int $followingId)
   {
        $follow = $this->model::where('user_id', $userId)
                              ->where('following_id', $followingId)
                              ->delete(); 

        if (!$follow) {
            return $this->model::create([
                    'user_id' => $userId,
                    'following_id' => $followingId    
                ]);
        }

        return $follow;
   }

   public function getFollowers(int $userId)
   {
        return $this->model::where('following_id', $userId)->get(); 
   }

   public function countFollowers(int $userId)
   {
        return $this->model::where('following_id', $userId)->count(); 
   }

   public function getFollowing(int $userId)
   {
        return $this->model::where('user_id', $userId)->get(); 
   }

   public function countFollowing(int $userId)
   {
        return $this->model::where('user_id', $userId)->get(); 
   }
}
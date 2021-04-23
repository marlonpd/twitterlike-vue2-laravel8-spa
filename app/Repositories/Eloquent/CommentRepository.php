<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;
use Illuminate\Support\Collection;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{

   /**
    * CommentRepository constructor.
    *
    * @param User $model
    */
   public function __construct(Comment $model)
   {
       parent::__construct($model);
   }

   public function findById(int $id)
   {
       return $this->model::find($id);
   }

   /**
    * @return Collection
    */
   public function all(): Collection
   {
       return $this->model->all();    
   }

   public function findByTweetId(string $tweetId): ?Collection 
   {
        return $this->model::where('tweet_id', $tweetId)->get();   
   }

   public function update(int $id, array $comment): int 
   {
        return $this->model::find($id)->update($comment);   
   }

    public function delete(string $id) 
    {
        return $this->model::where('id', $id)->delete();
    }
}
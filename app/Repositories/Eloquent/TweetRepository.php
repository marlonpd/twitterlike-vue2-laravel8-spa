<?php

namespace App\Repositories\Eloquent;

use App\Models\Tweet;
use App\Repositories\TweetRepositoryInterface;
use Illuminate\Support\Collection;

class TweetRepository extends BaseRepository implements TweetRepositoryInterface
{
    private $limit = 10;

   /**
    * TweetRepository constructor.
    *
    * @param Tweet $model
    */
   public function __construct(Tweet $model)
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

   public function findById(int $id)
   {
       return $this->model::find($id);
   }

   /**
    * @return Collection
    */
    public function findByPosterIdLimitedList(int $posterId, int $pageIndex): Collection
    {
        return $this->model::where('poster_id', $posterId)
                            ->limit($this->limit)
                            ->offset(($pageIndex-1)  * $this->limit)
                            ->get();
    }

    /**
    * @return Collection
    */
    public function countByPosterIdLimitedList(int $posterId, int $pageIndex): int
    {
        return $this->model::where('poster_id', $posterId)
                            ->limit($this->limit)
                            ->offset(($pageIndex-1)  * $this->limit)
                            ->count();
    }

    public function countByOwnerId(int $posterId): ?int
    {
        return $this->model::where('poster_id', $posterId)->count();   
    }

    public function search(string $searchKey): ?Collection
    {
        return $this->model::where('content', 'LIKE', "%{$searchKey}%")
                                ->get(); 
    }

    public function countSearch(string $posterId, string $searchKey): ?int
    {
        return $this->model::where('poster_id', $posterId)
                                ->where('content', 'LIKE', "%{$searchKey}%")
                                ->count();   
    }

    public function findByPosterId(int $posterId): ?Tweet {
            return $this->model::where('poster_id', $posterId)->get();   
    }

    public function update(int $id, array $tweet): int 
    {
            return $this->model::find($id)->update($tweet);   
    }

    public function delete(string $id) 
    {
        return $this->model::where('id', $id)->delete();
    }
}
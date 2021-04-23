<?php
namespace App\Repositories;

use Illuminate\Support\Collection;

interface TweetRepositoryInterface
{
   public function all(): Collection;
}
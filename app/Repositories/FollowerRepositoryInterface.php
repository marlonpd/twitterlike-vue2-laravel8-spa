<?php
namespace App\Repositories;

use Illuminate\Support\Collection;

interface FollowerRepositoryInterface
{
   public function all(): Collection;
}
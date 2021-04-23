<?php
namespace App\Repositories;

use Illuminate\Support\Collection;

interface CommentRepositoryInterface
{
   public function all(): Collection;
}
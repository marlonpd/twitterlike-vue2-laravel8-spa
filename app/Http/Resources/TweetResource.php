<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TweetResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'content' => $this->content,
            'posterId' => $this->poster_id,
            'updatedAt' => $this->updated_at,
            'createdAt' => $this->created_at,
        ];
        //return parent::toArray($request);
    }
}
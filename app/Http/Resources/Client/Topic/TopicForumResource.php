<?php

namespace App\Http\Resources\Client\Topic;

use App\Http\Resources\Client\Forum\TopicTagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicForumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'root' => $this->type,
        ];
    }
}

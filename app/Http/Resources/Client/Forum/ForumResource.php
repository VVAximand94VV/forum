<?php

namespace App\Http\Resources\Client\Forum;

use Illuminate\Http\Resources\Json\JsonResource;

class ForumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'children' => ChildrenForumResource::collection($this->children),
            //'topics' => TopicResource::collection($this->topics->where('status', '=', 1)),
        ];
    }
}

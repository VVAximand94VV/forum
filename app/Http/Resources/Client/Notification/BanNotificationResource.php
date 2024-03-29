<?php

namespace App\Http\Resources\Client\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class BanNotificationResource extends JsonResource
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
            'banEnd' => $this->banEnd->format('Y-m-d'),
        ];
    }
}

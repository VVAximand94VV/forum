<?php

namespace App\Http\Resources\Client\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $permissions = $this->permissions;
        $permissions->merge($this->permissionsThroughRole());
        $userPermissions = $permissions->flatten(1)->pluck('slug');

        return [
            'id' => $this->id,
            'login' => $this->login,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'name' => $this->firstName . ' ' . $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role->slug,
            'permissions' => $userPermissions,
            'stats' => [
                'topics' => $this->topics->count(),
                'posts' => $this->posts->count(),
                'carma' => 0,
            ],
            'isBanned' => false,
            'emailVerified_at' => $this->email_verified_at->format('Y-m-d'),
            'register_at' => $this->created_at->format('Y-m-d'),
            'topics' => $this->topics,
            'posts' => $this->posts,
            'likes' => $this->likes,
            'bookmarks' => $this->bookmarks,
        ];
    }
}
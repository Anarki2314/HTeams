<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'inviteCode' => $this->invite_code,
            'members' => TeamMembersResource::collection($this->members),
            'isBanned' => $this->isBanned(),
            'leader' => [
                'avatar' => ($this->leader->avatar) ? new FileResource($this->leader->avatar) : [
                    'path' => '/assets/img/avatar.jpg',
                    'name' => 'avatar.jpg'
                ],
            ]
        ];
    }
}

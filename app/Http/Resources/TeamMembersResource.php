<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMembersResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'isLeader' => $this->isLeader(),
            'avatar' => ($this->avatar) ? new FileResource($this->avatar) : [
                'path' => '/assets/img/avatar.jpg',
                'name' => 'avatar.jpg'
            ]
        ];
    }
}

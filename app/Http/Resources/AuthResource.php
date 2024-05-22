<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'token' => $this->resource['token'],
            'user' => [
                'id' => $this->resource['user']->id,
                'email' => $this->resource['user']->email,
                'role' => $this->resource['user']->role->title,
                'isVerified' => $this->resource['user']->isVerified(),

            ],
        ];

        if ($this->resource['user']->isUser()) {
            $data['user']['isUser'] = true;
            if ($this->resource['user']->team) {
                $data['user']['haveTeam'] = true;
                $data['user']['isLeader'] = $this->resource['user']->isLeader();
            }
        } elseif ($this->resource['user']->isOrganizer()) {
            $data['user']['isOrganizer'] = true;
        } elseif ($this->resource['user']->isAdmin()) {
            $data['user']['isAdmin'] = true;
        }
        return $data;
    }
}

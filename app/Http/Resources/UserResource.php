<?php

namespace App\Http\Resources;

use App\Models\Ban;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isBanned = $this->isBanned();
        $data = [
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'role' => $this->role->title,
            'avatar' => ($this->avatar) ? new FileResource($this->avatar) : [
                'path' => '/assets/img/avatar.jpg',
                'name' => 'Аватар',
            ],
            'isBanned' => $isBanned,
        ];

        if ($isBanned) {
            $ban = Ban::where('user_id', $this->id)->where('expires_at', '>', now())->first();
            $data['ban'] = [
                'reason' => $ban->reason,
                'expires_at' => $ban->expires_at,
            ];
        }

        if ($this->isUser()) {
            $data['name'] = $this->name;
            $data['surname'] = $this->surname;
            $data['isUser'] = true;
            if ($this->team) {
                $data['haveTeam'] = true;
                $data['isLeader'] = $this->isLeader();
            }
        } elseif ($this->isOrganizer()) {
            $data['orgName'] = $this->orgName;
            $data['isOrganizer'] = true;
        } elseif ($this->isAdmin()) {
            $data['isAdmin'] = true;
        }

        return $data;
    }
}

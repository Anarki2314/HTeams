<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationInvitesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'type' => $this->type,
        ];

        switch ($this->type) {
            case 'invite':
                $data['teamName'] = $this->team->title;
                $data['teamId'] = $this->team->id;
                break;
            case 'join':
                $data['userId'] = $this->fromUser->id;
                $data['email'] = $this->fromUser->email;
                break;
            default:
                break;
        }



        return $data;
    }
}

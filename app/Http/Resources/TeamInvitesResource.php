<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamInvitesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'userId' => $this->id,

        ];
        if ($this->email) {
            $data['email'] = $this->email;
        }
        if ($this->title) {
            $data['title'] = $this->title;
        }

        return $data;
    }
}

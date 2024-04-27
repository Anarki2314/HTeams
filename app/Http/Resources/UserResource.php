<?php

namespace App\Http\Resources;

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
        $data = [
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'role' => $this->role->title
        ];

        if ($this->isUser()) {
            $data['name'] = $this->name;
            $data['surname'] = $this->surname;
            $data['isUser'] = true;
        } elseif ($this->isOrganizer()) {
            $data['orgName'] = $this->orgName;
            $data['isOrganizer'] = true;
        } elseif ($this->isAdmin()) {
            $data['isAdmin'] = true;
        }

        return $data;
    }
}

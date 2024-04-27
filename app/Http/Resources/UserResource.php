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
        ];

        if ($this->isUser() || ($this->role->title === 'Admin' && auth()->user()->isAdmin())) {
            $data['name'] = $this->name;
            $data['surname'] = $this->surname;
            $data['isUser'] = true;
            $data['role'] = $this->role_id;
        } elseif ($this->isOrganizer() || ($this->role->title === 'Admin' && auth()->user()->isAdmin())) {
            $data['orgName'] = $this->orgName;
            $data['isOrganizer'] = true;
            $data['role'] = $this->role_id;
        }

        return $data;
    }
}

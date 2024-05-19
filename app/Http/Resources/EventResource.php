<?php

namespace App\Http\Resources;

use App\Models\EventStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date_registration' => $this->date_registration,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'image' => new FileResource($this->image),
            'status' => $this->status->title,
            'creator' => $this->creator->orgName,
            'tags' => TagResource::collection($this->tags),
            'prizes' => PrizesResource::collection($this->prizes),
        ];
        $user = $request->user('sanctum');
        if ($user && $user->isUser() && $user->team) {
            $data['isJoined'] = $this->isJoined($user);
        }
        if ($this->status_id == EventStatus::getByTitle('Началось')->id) {
            $data['task'] = new FileResource($this->task);
        }

        return $data;
    }
}

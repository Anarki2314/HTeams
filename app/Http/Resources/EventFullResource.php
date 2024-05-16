<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventFullResource extends JsonResource
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
            'task' => new FileResource($this->task),
            'tags' => TagResource::collection($this->tags),
            'prizes' => PrizesResource::collection($this->prizes),
            'status' => $this->status->title,
            'creator' => $this->creator->orgName,
        ];
        return $data;
    }
}

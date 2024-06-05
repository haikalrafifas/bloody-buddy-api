<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'location' => new LocationResource($this->whenLoaded('location')),
            'current_daily_quota' => $this->current_daily_quota,
            'total_daily_quota' => $this->daily_quota,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'location' => new LocationResource($this->whenLoaded('location')),
        ];
    }
}

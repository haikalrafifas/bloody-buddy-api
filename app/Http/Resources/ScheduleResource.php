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
            'address' => $this->address,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image' => $this->image,
            // 'created_at' => $this->created_at,
        ];
    }
}

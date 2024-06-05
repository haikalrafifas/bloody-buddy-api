<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonorScheduleResource extends JsonResource
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
            'donor_id' => $this->donor_id,
            'schedule_id' => $this->schedule_id,
            'schedules' => $this->whenLoaded('schedule', function () {
                return [
                    'daily_quota' => $this->schedule->daily_quota
                ];
            }),
            'status_id' => $this->status_id,
            'donor_applicant' => new DonorApplicantResource($this->whenLoaded('donorApplicant')),
            'schedule' => new ScheduleResource($this->whenLoaded('schedule')),
            'donor_status' => new DonorStatusResource($this->whenLoaded('donorStatus')),
            'created_at' => $this->created_at,
        ];
    }
}

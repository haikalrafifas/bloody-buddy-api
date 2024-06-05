<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonorApplicantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        // return [
        //     'uuid' => $this->uuid,
        //     'nik' => $this->nik,
        //     'name' => $this->name,
        //     'dob' => $this->dob,
        //     'gender' => $this->gender,
        //     'phone_number' => $this->phone_number,
        //     'address' => $this->address,
            
        //     'blood_type' => $this->blood_type,
        //     'body_mass' => $this->body_mass,
        //     'hemoglobin_level' => $this->hemoglobin_level,
        //     'blood_pressure' => $this->blood_pressure,
        //     'medical_conditions' => $this->medical_conditions,
            
        //     'created_at' => $this->created_at,

        //     'schedules' => $this->whenLoaded('schedules', function() {
        //         return $this->donorSchedules->map(function ($donorSchedule) {
        //             return [
        //                 'uuid' => $donorSchedule->uuid,
        //                 'schedule' => $donorSchedule->schedule ? [
        //                     'uuid' => $donorSchedule->schedule->uuid,
        //                     'daily_quota' => $donorSchedule->schedule->daily_quota,
        //                     'start_date' => $donorSchedule->schedule->start_date,
        //                     'end_date' => $donorSchedule->schedule->end_date,
        //                     'location' => $donorSchedule->schedule->location ? [
        //                         'uuid' => $donorSchedule->schedule->location->uuid,
        //                         'name' => $donorSchedule->schedule->location->name,
        //                         'address' => $donorSchedule->schedule->location->address,
        //                         'image' => $donorSchedule->schedule->location->image,
        //                     ] : null,
        //                 ] : null,
        //                 'status' => $donorSchedule->donorStatus ? [
        //                     'name' => $donorSchedule->donorStatus->name,
        //                     'description' => $donorSchedule->donorStatus->description,
        //                 ] : null,
        //             ];
        //         });
        //     }),
        // ];

        return [
            'uuid' => $this->uuid,
            
            'user' => new UserResource($this->whenLoaded('user')),
            'schedule' => new ScheduleResource($this->whenLoaded('schedule')),
            'status' => new DonorStatusResource($this->whenLoaded('status')),

            'nik' => $this->nik,
            'name' => $this->name,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            
            'blood_type' => $this->blood_type,
            'body_mass' => $this->body_mass,
            'hemoglobin_level' => $this->hemoglobin_level,
            'blood_pressure' => $this->blood_pressure,
            'medical_conditions' => $this->medical_conditions,

            'created_at' => $this->created_at,
        ];
    }

    // public function 
}

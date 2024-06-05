<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\DonorStatusRequest;
use App\Http\Resources\DonorStatusResource;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\DonorStatus;


class DonorStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonorStatusRequest $request)
    {
        try {
            $donorStatus = DonorStatus::create([
                'uuid' => Str::uuid()->toString(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $data = new DonorStatusResource($donorStatus);

            return $this->sendResponse($data, 'Successfully add donor status data!');
        } catch (Exception $e) {
            return $this->sendError('Internal Server Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // ONLY ADMIN CAN UPDATE DONOR APPLICANTS' DATA!
        // Also, they can only edit the status

        $validator = Validator::make($request->all(), [
            'action' => 'required|string',
        ]);

        if ( $validator->fails() ) {
            return $this->sendError('Bad Request', errors: $validator->errors(), status: Response::HTTP_BAD_REQUEST);
        }

        $donorSchedule = DonorSchedule::where('uuid', $uuid);

        // uuid: From donor_schedules, not donor_applicants!
        if ( !($donorScheduleData = $donorSchedule->first()) ) {
            return $this->sendError('Not Found', 'Donor applicant schedule was not found!', Response::HTTP_NOT_FOUND);
        }

        $status = DonorStatus::findOrFail($donorScheduleData->status_id);

        $validActions = [
            // action => [status, incrementBy
            'approve' => ['Waiting List', 1],
            'reject' => ['Waiting List', 4],
            'start' => ['Approved', 1],
            'done' => ['Ongoing', 1],
        ];

        $action = $request->action;

        if ( !isset($validActions[$action]) ) {
            return $this->sendError('Bad Request', 'Unknown action!', Response::HTTP_BAD_REQUEST);
        }

        // Change the status according to action and current state of status
        if ( $validActions[$action][0] === $status->name ) {
            $donorSchedule->update([
                'status_id' => $status->id + $validActions[$request->action][1],
            ]);
        } else {
            return $this->sendError('Bad Request', 'Invalid action to status!', Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = new DonorApplicantResource(
                DonorApplicant::with([
                    'donorSchedules.schedule.location',
                    'donorSchedules.donorStatus',
                ])->orderBy('created_at', 'desc')->where('donor_id', Auth::id())->first()
            );

            return $this->sendResponse($data, 'Successfully add donor applicant data!');
        } catch (Exception $e) {
            return $this->sendError('Internal Server Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

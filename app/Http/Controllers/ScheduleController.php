<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Schedule;
use App\Models\Donor;
use App\Models\DonorSchedule;
use App\Http\Requests\ScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\DonorScheduleResource;
use Illuminate\Support\Str;

class ScheduleController extends Controller
{
    public function __construct()
    {
    $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::all();

        $data = ScheduleResource::collection($schedules);

        return $this->sendResponse($data, 'Successfully get schedules!');

        // $schedules = DonorSchedule::with('donor')->where('user_id', Auth::id());

        // $data = $schedules;
        // // $data = ScheduleResource::collection($schedules);

        // return $this->sendResponse('Successfully get schedules!', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request)
    {
        if ( !($donor = Donor::where('user_id', Auth::id())->where('status_id', 3)->first()) ) {
            return $this->sendError('Not Found', 'You have no approved donor submissions yet!', Response::HTTP_NOT_FOUND);
        }
        
        if ( !($schedule = Schedule::where('uuid', $request->schedule_uuid)->first()) ) {
            return $this->sendError('Not Found', 'Schedule not found!', Response::HTTP_NOT_FOUND);
        }
        
        try {
            $donorId = $donor->id;
            $scheduleId = $schedule->id;

            $donorSchedule = DonorSchedule::create([
                'uuid' => Str::uuid()->toString(),
                'donor_id' => $donorId,
                'schedule_id' => $scheduleId,
            ]);

            $donorScheduleData = $donorSchedule->with('donor', 'schedule')->orderBy('created_at', 'desc')->first();

            // $data = $donorScheduleData;

            $data = new DonorScheduleResource($donorScheduleData);

            return $this->sendResponse($data, 'Successfully add new donor schedule!');
        } catch ( \Exception $e ) {
            return $this->sendError('Internal Server Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // GET (byID atau apa)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // PATCH
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // POST /api/schedules/{id}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // DELETE
    }
}

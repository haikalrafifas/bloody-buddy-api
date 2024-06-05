<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Schedule;
use App\Models\Location;
use App\Http\Requests\ScheduleRequest;
use App\Http\Resources\ScheduleResource;
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
        // todo: add count daily quota
        $schedules = Schedule::with('location')->get();

        // $data = ScheduleResource::collection($schedules);
        $data = $schedules->map(function ($schedule) {
            return [
                'uuid' => $schedule->uuid,
                'location' => $schedule->location,
                'daily_quota' => $schedule->daily_quota,
                'current_daily_quota' => $schedule->getCurrentDailyQuotaAttribute($schedule->id),
                'start_date' => $schedule->start_date,
                'end_date' => $schedule->end_date,
            ];
        });

        return $this->sendResponse($data, 'Successfully get schedules!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request)
    {
        if ( !($location = Location::where('uuid', $request->location_uuid)->first()) ) {
            return $this->sendError('Bad Request', 'Location not found!', Response::HTTP_BAD_REQUEST);
        }

        try {
            $schedule = Schedule::create([
                'uuid' => Str::uuid()->toString(),
                'location_id' => (int) $location->id,
                'daily_quota' => (int) $request->daily_quota,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $data = new ScheduleResource(
                $schedule::with(['location'])->orderBy('created_at', 'desc')->first()
            );

            return $this->sendResponse($data, 'Successfully add new schedule!');
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
    public function update(Request $request, string $uuid)
    {
        // request->nik
        // 
        // POST /api/schedules/{id}

        if ( !($location = Location::where('uuid', $request->location_uuid)->first()) ) {
            return $this->sendError('Bad Request', 'Location not found!', Response::HTTP_BAD_REQUEST);
        }

        try {
            $schedule = Schedule::create([
                'uuid' => Str::uuid()->toString(),
                'location_id' => (int) $location->id,
                'daily_quota' => (int) $request->daily_quota,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $data = new ScheduleResource(
                $schedule::with(['location'])->orderBy('created_at', 'desc')->first()
            );

            return $this->sendResponse($data, 'Successfully add new schedule!');
        } catch ( \Exception $e ) {
            return $this->sendError('Internal Server Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        try {
            if ( !($schedule = Schedule::with('location')->where('uuid', $uuid)) ) {
                return $this->sendError();
            }

            $scheduleData = $schedule->first();

            $schedule->delete();

            $data = new ScheduleResource(
                $schedule->with(['location'])->orderBy('created_at', 'desc')->first()
            );

            return $this->sendResponse($data, 'Successfully add new schedule!');
        } catch ( \Exception $e ) {
            return $this->sendError('Internal Server Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\DonorApplicantRequest;
use App\Http\Resources\DonorApplicantResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\DonorApplicant;
use App\Models\Schedule;
use App\Models\DonorSchedule;
use App\Models\DonorStatus;

class DonorApplicantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'/*, ['except' => ['index', 'show']]*/);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donors = DonorApplicant::with([
            'donorSchedules.schedule.location',
            'donorSchedules.donorStatus',
        ]);

        // If not admin, then only get their respective donor data by applicants\ account
        if ( !Auth::user()->is_admin ) {
            $donors = $donors->where('user_id', Auth::id());
        }

        $data = DonorApplicantResource::collection($donors->get());

        return $this->sendResponse($data, 'Successfully get donor applicants!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonorApplicantRequest $request)
    {
        $userId = Auth::id();

        // Age must be between 17 to 60 y.o
        $age = Carbon::now()->diffInYears($request->dob);
        if ( $age < 17 || $age > 60 ) {
            return $this->sendError('Bad Request', 'Invalid age!', Response::HTTP_BAD_REQUEST);
        }

        // Check donor cooldown of 2 months
        if (
            ($donor = DonorApplicant::where('user_id', $userId)->orderBy('created_at', 'desc')->first())
            &&
            Carbon::now()->diffInMonths($donor->created_at) < 2
        ) {
            return $this->sendError(
                'Bad Request',
                'You have already submitted a donor form! Please wait for 2 months after the latest submission.',
                Response::HTTP_BAD_REQUEST,
            );
        }

        if ( !($schedule = Schedule::where('uuid', $request->schedule_uuid)->first()) ) {
            return $this->sendError('Not Found', 'Schedule was not found!', Response::HTTP_NOT_FOUND);
        }

        // Check if the quota is exceeded
        try {
            DonorSchedule::checkQuota($schedule->id);
        } catch (\Exception $e) {
            return $this->sendError('Bad Request', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $donor = DonorApplicant::create([
                'uuid' => Str::uuid()->toString(),
                'name' => $request->name,
                'nik' => $request->nik,
                'user_id' => $userId,
                'dob' => $request->dob,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'body_mass' => (int) $request->body_mass,
                'hemoglobin_level' => (int) $request->hemoglobin,
                'blood_type' => $request->blood_type,
                'blood_pressure' => $request->blood_pressure,
                'medical_conditions' => $request->medical_conditions,
            ]);

            DonorSchedule::create([
                'uuid' => Str::uuid()->toString(),
                'donor_id' => $donor->id,
                'schedule_id' => $schedule->id,
                'status_id' => DonorStatus::where('name', 'Waiting List')->first()->id,
            ]);

            // $data = new DonorApplicantResource($donor);
            $data = new DonorApplicantResource(
                $donor->with([
                    'donorSchedules.schedule.location',
                    'donorSchedules.donorStatus',
                ])->orderBy('created_at', 'desc')->first()
            );

            return $this->sendResponse($data, 'Successfully add donor applicant data!');
        } catch (Exception $e) {
            return $this->sendError('Internal Server Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        // ONLY ADMIN CAN UPDATE DONOR APPLICANTS' DATA!
        // Also, they can only edit the status

        $validator = Validator::make($request->all(), [
            'action' => 'required|string',
        ]);

        if ( $validator->fails() ) {
            return $this->sendError('Bad Request', errors: $validator->errors(), status: Response::HTTP_BAD_REQUEST);
        }

        // uuid: From donor_schedules, not donor_applicants!
        if ( !($donorSchedule = DonorSchedule::where('uuid', $uuid))->first() ) {
            return $this->sendError('Not Found', 'Donor applicant schedule was not found!', Response::HTTP_NOT_FOUND);
        }

        $status = DonorStatus::find($donorSchedule->status_id)->first();

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

        if ( $validActions[$action][0] === $status->name ) {
            $donorSchedule->update([
                'status_id' => $status->id + $validActions[$request->action][1],
            ]);
        }

        try {
            $data = new DonorApplicantResource(
                DonorApplicant::with([
                    'donorSchedules.schedule.location',
                    'donorSchedules.donorStatus',
                ])->orderBy('created_at', 'desc')->first()
                ->where('uuid', Auth::id())
            );

            return $this->sendResponse($data, 'Successfully add donor applicant data!');
        } catch (Exception $e) {
            return $this->sendError('Internal Server Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

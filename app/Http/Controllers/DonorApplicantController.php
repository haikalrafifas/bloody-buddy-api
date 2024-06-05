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
use App\Models\DonorStatus;

class DonorApplicantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donors = DonorApplicant::with([
            'user',
            'schedule.location',
            'status',
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
        // Age must be between 17 to 60 y.o
        $age = Carbon::now()->diffInYears($request->dob);
        if ( $age < 17 || $age > 60 ) {
            return $this->sendError('Bad Request', 'Invalid age!', Response::HTTP_BAD_REQUEST);
        }

        // Check donor cooldown of 2 months
        if (
            ($donor = DonorApplicant::where('nik', $request->nik)->orderBy('created_at', 'desc')->first())
            &&
            Carbon::now()->diffInMonths($donor->created_at) < 2
        ) {
            return $this->sendError(
                'Bad Request',
                'You have already submitted a donor form! Please wait for 2 months after the latest submission.',
                Response::HTTP_BAD_REQUEST,
            );
        }

        // Sequence of medical conditions checking
        // check body_mass, hemoglobin_level, blood_pressure, medical_history
        // if ( $request->body_mass < 45 ) {
        //     return $this->sendError('Bad Request', '', Response::HTTP);
        // }

        // if ( $request->hemoglobin_level < 12.5 || $request->hemoglobin_level > 17.0 ) {
        //     return $this->sendError();
        // }

        // [$sistole, $diastole] = explode('/', $request->blood_pressure);
        // if ( $sistole < 100 || $sistole > 170 ) {
        //     return $this->sendError();
        // }
        // if ( $diastole < 70 || $diastole > 100 ) {
        //     return $this->sendError();
        // }

        // Check the availability of the schedule
        if ( !($schedule = Schedule::where('uuid', $request->schedule_uuid)->first()) ) {
            return $this->sendError('Not Found', 'Schedule was not found!', Response::HTTP_NOT_FOUND);
        }

        // Check if the schedule's daily quota is exceeded
        try {
            DonorApplicant::checkQuota($schedule->id);
        } catch (\Exception $e) {
            return $this->sendError('Bad Request', $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $donorStatus = DonorStatus::where('name', 'Waiting List')->first()->id;
            $donorStatusId = isset($donorStatus) ? $donorStatus : 1;

            $donor = DonorApplicant::create([
                'uuid' => Str::uuid()->toString(),
                'name' => $request->name,
                'nik' => $request->nik,
                'user_id' => Auth::id(),
                'schedule_id' => $schedule->id,
                'status_id' => $donorStatusId,
                'dob' => $request->dob,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'body_mass' => (int) $request->body_mass,
                'hemoglobin_level' => (int) $request->hemoglobin,
                'blood_type' => $request->blood_type,
                'blood_pressure' => $request->blood_pressure,
                'medical_conditions' => $request->medical_conditions,
            ]);

            // $data = new DonorApplicantResource($donor);
            $data = new DonorApplicantResource(
                $donor->with([
                    'user',
                    'schedule.location',
                    'status',
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

        if ( !($donorApplicant = DonorApplicant::where('uuid', $uuid))->first() ) {
            return $this->sendError('Not Found', 'Donor applicant data was not found!', Response::HTTP_NOT_FOUND);
        }

        $donorApplicantData = $donorApplicant->first();

        $status = DonorStatus::find($donorApplicantData->status_id);

        $validActions = [
            // action => [status, incrementBy, 'actionAlias]
            'approve' => ['Waiting List', 1],
            'reject' => ['Waiting List', 4],
            'cancel-apply' => ['Waiting List', 5],
            'start' => ['Approved', 1],
            'cancel-approval' => ['Approved', 5],
            'done' => ['Ongoing', 1],
        ];

        $action = $request->action;

        $chosenAction = $validActions[$action];

        if ( !isset($chosenAction) ) {
            return $this->sendError('Bad Request', 'Unknown action!', Response::HTTP_BAD_REQUEST);
        }

        // Change the status according to action and current state of status
        if ( $chosenAction[0] === $status->name ) {
            $donorApplicant->update([
                'status_id' => $status->id + $chosenAction[1],
            ]);
        } else {
            return $this->sendError('Bad Request', 'Invalid action to status!', Response::HTTP_BAD_REQUEST);
        }

        try {
            $data = new DonorApplicantResource(
                DonorApplicant::with([
                    'user',
                    'schedule.location',
                    'status',
                ])->orderBy('created_at', 'desc')->where('id', Auth::id())->first()
            );

            $donorStatus = str_replace('-', ' ', $action);

            return $this->sendResponse($data, 'Successfully change donor applicant status: ' . $donorStatus);
        } catch (Exception $e) {
            return $this->sendError('Internal Server Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $uuid)
    {
        try {
            if ( !($donorApplicants = DonorApplicant::where('uuid', $uuid)) ) {
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

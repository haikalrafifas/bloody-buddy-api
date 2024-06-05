<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Donor;
use App\Http\Requests\DonorRequest;
use App\Http\Resources\DonorResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DonorController extends Controller
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonorRequest $request)
    {
        // Age must be between 17 to 60 y.o
        $age = Carbon::now()->diffInYears($request->dob);
        if ($age < 17 || $age > 60) {
            return $this->sendError('Bad Request', 'Invalid age!', Response::HTTP_BAD_REQUEST);
        }

        // Check donor cooldown of 2 months
        if (
            ($donor = Donor::where('user_id', Auth::id())->orderBy('created_at', 'desc')->first())
            &&
            Carbon::now()->diffInMonths($donor->created_at) < 2
        ) {
            return $this->sendError(
                'Bad Request',
                'You have already submitted a donor form! Please wait for 2 months after the latest submission.',
                Response::HTTP_BAD_REQUEST,
            );
        }

        try {
            $donor = Donor::create([
                'uuid' => Str::uuid()->toString(),
                'name' => $request->name,
                'nik' => $request->nik,
                'user_id' => Auth::id(),
                'status_id' => 1,
                'dob' => $request->dob,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'body_mass' => (int) $request->body_mass,
                'hemoglobin_level' => (int) $request->hemoglobin,
                'blood_type' => $request->blood_type,
                'blood_pressure' => $request->blood_pressure,
                'medical_conditions' => $request->medical_conditions,
            ]);

            $data = new DonorResource($donor);

            return $this->sendResponse($data, 'Successfully add donor!');
        } catch (\Exception $e) {
            return $this->sendError('Internal Server Error', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $donors = Donor::where('user_id', Auth::id());

        $data = DonorResource::collection($donors);

        return $this->sendResponse(message: 'Successfully get donors!', data: $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

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
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donors = Donor::all();

        $data = DonorResource::collection($donors);

        return $this->sendResponse(message: 'Successfully get donors!', data: $data);
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
    public function store(DonorRequest $request)
    {
        $age = Carbon::now()->diffInYears($request->dob);
        if ( $age < 17 || $age > 60 ) {
            return $this->sendError('Bad Request', 'Invalid age!', Response::HTTP_BAD_REQUEST);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function checkLatestDonor()
    {
        if ( !($donor = Donor::where('user_id', Auth::id())->first()) ) {
            return $this->sendError('Not Found', 'No latest donor were found!', Response::HTTP_NOT_FOUND);
        }

        if ( Carbon::now()->diffInMonths($donor->created_at) < 2 ) {
            return $this->sendError('Bad Request', ['is_valid' => false], Response::HTTP_BAD_REQUEST);
        }

        return $this->sendResponse(['is_valid' => true], 'You are allowed to fill another form!');
    }
}

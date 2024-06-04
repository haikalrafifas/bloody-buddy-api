<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DonorStatus;
use App\Http\Requests\DonorRequest;
use App\Http\Resources\DonorResource;

class DonorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'DonorStatus']]);
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
    public function store(Request $request)
    {
        //
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

    public function DonorStatus()
    {
        $status = DonorStatus::all();
        return $this->sendResponse(message:'', data: $status);
    }
}

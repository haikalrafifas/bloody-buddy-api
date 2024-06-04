<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Schedule;
use App\Http\Resources\ScheduleResource;

class ScheduleController extends Controller
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
        // GET
        $schedules = Schedule::all();

        $data = ScheduleResource::collection($schedules);

        return $this->sendResponse('Successfully get schedules!', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // PUT
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // POST /api/schedules
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

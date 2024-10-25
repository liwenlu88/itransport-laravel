<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PositionStatus\StorePositionStatusRequest;
use App\Http\Requests\Admin\PositionStatus\UpdatePositionStatusRequest;
use App\Models\PositionStatus;

class PositionStatusController extends Controller
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
    public function store(StorePositionStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PositionStatus $positionStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PositionStatus $positionStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePositionStatusRequest $request, PositionStatus $positionStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PositionStatus $positionStatus)
    {
        //
    }
}

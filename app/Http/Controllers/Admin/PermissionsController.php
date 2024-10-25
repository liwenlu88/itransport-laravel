<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionsRequest;
use App\Http\Requests\UpdatePermissionsRequest;
use App\Models\Permissions;

class PermissionsController extends Controller
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
    public function store(StorePermissionsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Permissions $permissions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permissions $permissions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionsRequest $request, Permissions $permissions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permissions $permissions)
    {
        //
    }
}

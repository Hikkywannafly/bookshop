<?php

namespace App\Http\Controllers;

use App\Models\Formality;
use App\Http\Requests\StoreFormalityRequest;
use App\Http\Requests\UpdateFormalityRequest;

class FormalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFormalityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormalityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Formality  $formality
     * @return \Illuminate\Http\Response
     */
    public function show(Formality $formality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Formality  $formality
     * @return \Illuminate\Http\Response
     */
    public function edit(Formality $formality)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFormalityRequest  $request
     * @param  \App\Models\Formality  $formality
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFormalityRequest $request, Formality $formality)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Formality  $formality
     * @return \Illuminate\Http\Response
     */
    public function destroy(Formality $formality)
    {
        //
    }
}

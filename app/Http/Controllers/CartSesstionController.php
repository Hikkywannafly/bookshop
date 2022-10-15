<?php

namespace App\Http\Controllers;

use App\Models\CartSesstion;
use App\Http\Requests\StoreCartSesstionRequest;
use App\Http\Requests\UpdateCartSesstionRequest;

class CartSesstionController extends Controller
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
     * @param  \App\Http\Requests\StoreCartSesstionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartSesstionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CartSesstion  $cartSesstion
     * @return \Illuminate\Http\Response
     */
    public function show(CartSesstion $cartSesstion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CartSesstion  $cartSesstion
     * @return \Illuminate\Http\Response
     */
    public function edit(CartSesstion $cartSesstion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCartSesstionRequest  $request
     * @param  \App\Models\CartSesstion  $cartSesstion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartSesstionRequest $request, CartSesstion $cartSesstion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CartSesstion  $cartSesstion
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartSesstion $cartSesstion)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BookDetail;
use App\Http\Requests\StoreBookDetailRequest;
use App\Http\Requests\UpdateBookDetailRequest;

class BookDetailController extends Controller
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
     * @param  \App\Http\Requests\StoreBookDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookDetail  $bookDetail
     * @return \Illuminate\Http\Response
     */
    public function show(BookDetail $bookDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookDetail  $bookDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(BookDetail $bookDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookDetailRequest  $request
     * @param  \App\Models\BookDetail  $bookDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookDetailRequest $request, BookDetail $bookDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookDetail  $bookDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookDetail $bookDetail)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function getSubCategory(Request $request)
    {
        $sub_category_id = DB::table('sub_categories')->where('slug', $request->sub_slug)->first()->id;
        $book_quantity = DB::table('books')->where('sub_category_id', '=', $sub_category_id)->count();
        $books = DB::table('books')
            ->leftJoin('ratings', 'books.id', '=', 'ratings.book_id')
            ->select([
                'books.id', 'books.name', 'books.slug', 'books.default_image', 'books.price', 'discount',
                DB::raw('AVG(ratings.rating) as rating')
            ])
            ->where('sub_category_id', '=',  $sub_category_id)
            ->groupBy('books.id')
            ->get();
        return response()->json([
            'status' => 'success',
            'records' => $book_quantity,
            'books' => $books

        ], 200);
    }

    public function getSuppliers(Request $request)
    {
        try {
            $sub_category_id = DB::table('sub_categories')->where('slug', $request->sub_slug)->first()->id;
           
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found'
            ], 500);
        }
    }
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
     * @param  \App\Http\Requests\StoreSubCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategory $subCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(SubCategory $subCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubCategoryRequest  $request
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subCategory)
    {
        //
    }
}

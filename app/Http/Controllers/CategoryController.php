<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function getCategory(Request $request)
    {
        try {

            $category_id = DB::table('categories')->where('slug', $request->slug)->first()->id;
            $book_quantity = DB::table('books')->where('category_id', '=', $category_id)->count();
            $books = DB::table('books')
                ->leftJoin('ratings', 'books.id', '=', 'ratings.book_id')
                ->select([
                    'books.id', 'books.name', 'books.slug', 'books.default_image', 'books.price', 'discount',
                    DB::raw('AVG(ratings.rating) as rating')
                ])
                ->where('category_id', '=', $category_id)
                ->groupBy('books.id')
                ->get();

            return response()->json([
                'status' => 'success',
                'records' => $book_quantity,
                'books' => $books

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSuppliers(Request $request)
    {
        try {
            $category_id = DB::table('categories')
                ->where('slug', $request->slug)->first();

            $sub_category_id = DB::table('sub_categories')
                ->where('slug', $request->slug)->first();

            if ($category_id) {
                $suppliers = DB::table('books')
                    ->leftJoin('suppliers', 'books.supplier_id', '=', 'suppliers.id')
                    ->select([
                        'suppliers.id', 'suppliers.name', 'suppliers.slug', 'suppliers.logo'
                    ])
                    ->where('category_id', '=', $category_id->id)
                    ->groupBy('suppliers.id')
                    ->get();
            }
            if ($sub_category_id) {
                $suppliers = DB::table('books')
                    ->leftJoin('suppliers', 'books.supplier_id', '=', 'suppliers.id')
                    ->select([
                        'suppliers.id', 'suppliers.name', 'suppliers.slug', 'suppliers.logo'
                    ])
                    ->where('sub_category_id', '=', $sub_category_id->id)
                    ->groupBy('suppliers.id')
                    ->get();
            }
            return response()->json([
                'status' => 'success',
                'suppliers' => $suppliers,

            ]);
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
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}

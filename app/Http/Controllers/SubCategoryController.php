<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function getSubSuppliers(Request $request)
    {
        try {
            $suppliers_querry = Book::query()
                ->leftJoin('suppliers', 'suppliers.id', '=', 'books.supplier_id')
                ->select([
                    'suppliers.id', 'suppliers.name', 'suppliers.slug', 'suppliers.logo',
                ])
                ->groupBy('suppliers.id');

            if ($request->sub_slug && $request->slug != 'all-category') {
                $category_id = DB::table('sub_categories')->where('slug', $request->sub_slug)->first()->id;
                $suppliers_querry->where('sub_category_id', '=', $category_id);
            }
            $suppliers = $suppliers_querry->get();
            return response()->json([
                'status' => 'success',
                'suppliers' => $suppliers,

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found',
                'message1' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $books_query = Book::query()
                ->leftJoin('ratings', 'books.id', '=', 'ratings.book_id')
                ->select([
                    'books.id', 'books.name', 'books.slug', 'books.default_image', 'books.price', 'discount',
                    DB::raw('AVG(ratings.rating) as rating')
                ])
                ->groupBy('books.id');
            $suppliers_querry = Book::query()
                ->leftJoin('suppliers', 'suppliers.id', '=', 'books.supplier_id')
                ->select([
                    'suppliers.id', 'suppliers.name', 'suppliers.slug', 'suppliers.logo',
                ])
                ->groupBy('suppliers.id');

            if ($request->sub_slug && $request->slug != 'all-category') {
                $category_id = DB::table('sub_categories')->where('slug', $request->sub_slug)->first()->id;
                $books_query->where('sub_category_id', '=',  $category_id);
                $suppliers_querry->where('sub_category_id', '=', $category_id);
            }

            $books = $books_query->paginate(4);
            $suppliers = $suppliers_querry->get();
            return response()->json([
                'status' => 'success',
                'books' =>  $books,
                'suppliers' => $suppliers,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
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

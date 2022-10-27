<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\BookPagination as ResourcesBookPagination;
use App\Models\Book;
use App\Models\Supplier;
use BookPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{


    public function getSuppliers(Request $request)
    {
        try {
            $suppliers_querry = Book::query()
                ->leftJoin('suppliers', 'suppliers.id', '=', 'books.supplier_id')
                ->select([
                    'suppliers.id', 'suppliers.name', 'suppliers.slug', 'suppliers.logo',
                ])
                ->groupBy('suppliers.id');

            if ($request->slug && $request->slug != 'all-category') {
                $category_id = DB::table('categories')->where('slug', $request->slug)->first()->id;
                $suppliers_querry->where('category_id', '=', $category_id);
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
            if ($request->slug && $request->slug != 'all-category') {
                $category_id = DB::table('categories')->where('slug', $request->slug)->first()->id;
                $books_query->where('category_id', '=', $category_id);
                $suppliers_querry->where('category_id', '=', $category_id);
            }
            if ($request->supplier) {
                $books_query->where('supplier_id', '=', $request->supplier);
                $suppliers_querry->where('supplier_id', '=', $request->supplier);
            }


            $books = $books_query->paginate(4);
            $suppliers = $suppliers_querry->get();
            return response()->json([
                'status' => 'success',
                'books' =>  $books,
                'suppliers' => $suppliers,
                'supplier_id' => $request->supplier,
                'type' => $request,

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

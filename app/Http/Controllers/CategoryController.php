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
                // ->leftJoin('ratings', 'books.id', '=', 'ratings.book_id')
                // ->select([
                //     'books.id', 'books.name', 'books.slug', 'books.default_image', 'books.price', 'discount', 'books.created_at',
                //     DB::raw('AVG(ratings.rating) as rating')
                // ])
                // ->groupBy('books.id');
                ->with('category')
                ->with('book_detail')
                ->with('sub_category')
                ->with('formality')
                ->with('supplier')
                ->with('rating', function ($query) {
                    $query->select('book_id', DB::raw('AVG(rating) as rating'))
                        ->groupBy('book_id');
                })
                ->orWhere('name', 'like', '%' . $request->search . '%')
                ->orWhereHas('category', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('sub_category', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('formality', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('supplier', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })
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
            // if ($request->search) {
            //     $books_query
            //         ->orWhere('name', 'like', '%' . $request->search . '%')
            //         ->orWhereHas('category', function ($query) use ($request) {
            //             $query->where('name', 'like', '%' . $request->search . '%');
            //         })
            //         ->orWhereHas('sub_category', function ($query) use ($request) {
            //             $query->where('name', 'like', '%' . $request->search . '%');
            //         })
            //         ->orWhereHas('formality', function ($query) use ($request) {
            //             $query->where('name', 'like', '%' . $request->search . '%');
            //         })
            //         ->orWhereHas('supplier', function ($query) use ($request) {
            //             $query->where('name', 'like', '%' . $request->search . '%');
            //         });
            // }
            if ($request->supplier) {
                $books_query->orWhere('supplier_id', '=', $request->supplier);
            }
            if ($request->from) {
                $books_query->orWhere('formality_id', '=', $request->from);
            }
            if ($request->price) {
                $price = explode("-", $request->price);
                $books_query->orWhereBetween('price', [$price[0], $price[1]]);
            }
            $SORTS = [
                'best' => [
                    'column' => 'sold',
                    'order' => 'desc'
                ],
                'desc' => [
                    'column' => 'price',
                    'order' => 'desc'
                ],
                'asc' => [
                    'column' => 'price',
                    'order' => 'asc'
                ],
                'sale' => [
                    'column' => 'discount',
                    'order' => 'desc'
                ],
                'newest' => [
                    'column' => 'created_at',
                    'order' => 'desc'
                ],
            ];
            if ($request->sort && array_key_exists($request->sort, $SORTS)) {
                $books_query->orderBy($SORTS[$request->sort]['column'], $SORTS[$request->sort]['order']);
            }

            $books = $books_query->paginate(12);
            $suppliers = $suppliers_querry->get();
            return response()->json([
                'status' => 'success',
                'books' =>  $books,
                'suppliers' => $suppliers,
                'breadcrumbs' => $this->getBreadcrumbs($request->slug),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getBreadcrumbs($slug)
    {
        $breadcrumbs = [];
        if ($slug && $slug != 'all-category') {
            $category = Category::where('slug', $slug)->first();
            $breadcrumbs[] = [
                'name' => $category->name,
                'slug' => $category->slug,
            ];
        } else {
            $breadcrumbs[] = [
                'name' => 'all category',
                'slug' => 'all-category',
            ];
        }
        return $breadcrumbs;
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

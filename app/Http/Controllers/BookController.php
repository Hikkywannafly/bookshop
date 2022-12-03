<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $book_query = Book::query()
            ->with('images')
            ->with('book_detail')
            ->with('category')
            ->with('sub_category')
            ->with('formality')
            ->with('supplier')
            ->with('rating', function ($query) {
                $query->select('book_id', DB::raw('AVG(rating) as rating'));
            })
            ->where('slug', $request->slug);
        return response()->json([
            'status' => 'success',
            'book' => $book_query->first(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $testt = $request->images;
        foreach ($testt as $key => $value) {
            $imgName = time() . '.' . $value->extension();
            $value->move(public_path('images'),  $imgName);
        }
        // $name = time() . '.' . $testt->extension();
        // $testt->move(public_path('images'), $name);

        return response()->json([
            'status' => 'success',
            'message' => $testt,


        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //    update product
        $book = Book::find($request->id);
        $book->name = $request->name;
        $book->slug = $request->slug;
        $book->price = $request->price;
        $book->discount = $request->discount;
        $book->description = $request->description;
        $book->category_id = $request->category_id;
        $book->sub_category_id = $request->sub_category_id;
        $book->formality_id = $request->formality_id;
        $book->supplier_id = $request->supplier_id;
        $book->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }

    public function search(Request $request)
    {
        $book_query = Book::query()
            ->with('category')
            ->with('sub_category')
            ->with('formality')
            ->with('supplier')
            ->where('name', 'like', '%' . $request->search . '%')
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
            ->limit(7)
            ->get([
                'id', 'name', 'slug', 'price', 'discount', 'default_image'
            ]);
        if ($book_query->count() > 0) {
            return response()->json([
                'status' => 'success',
                'books' => $book_query,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'books' => 'Không tìm thấy sản phẩm nào',
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class AuthAdminController extends Controller
{
    //
    public function index()
    {
        return response()->json([
            'message' => 'Welcome to Admin Dashboard'
        ]);
    }

    public function readProduct(Request $request)
    {
        $book_query = Book::query()
            ->with('images')
            ->with('book_detail')
            ->with('category')
            ->with('sub_category')
            ->with('formality')
            ->with('supplier')
            ->paginate(10);

        $statistic = DB::table('books')
            ->select(
                DB::raw('sum(quantity) as total_quantity'),
                DB::raw('sum(sold) as total_sold')
            )
            ->get();
        return response()->json([
            'status' => 'success',
            'book' => $book_query,
            'statistic' => $statistic
        ]);
    }
    public function create(Request $request)
    {

        $file = [];
        $images = $request->file('images');
        if ($request->file('images')) {
            foreach ($images as $image) {
                $name = rand() . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path() . '/images/', $name);
                $file[] = url('/images/' . $name);
            }
        }
        $data = $request->input('data');
        $data = json_decode($data, true);
        $book = Book::create([
            'name' => $data['name'],
            'price' => 10000,
            'quantity' => 100,
            'slug' => $data['slug'],
            'category_id' => 1,
            'sub_category_id' => 1,
            'default_image' => $file[0],
            'formality_id' => 1,
            'supplier_id' => 1,

        ]);
        return response()->json([
            'status' => 'success',
            'data' => $request->all(),
            'test' =>  explode(',', $data['price'])[0],

        ]);
    }
}

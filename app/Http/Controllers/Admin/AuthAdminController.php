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
}

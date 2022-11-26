<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use IntlChar;

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

        try {
            // vaidate
            // check book exist
            $data = json_decode($request->input('data'), true);
            $book = Book::where('name', $data['name'])->first();
            $slug = Book::where('slug', $data['slug'])->first();
            if ($book || $slug) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sản phẩm đã tồn tại'
                ]);
            }

            $file = [];
            $images = $request->file('images');
            if ($request->file('images')) {
                foreach ($images as $image) {
                    $name = rand() . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path() . '/images/', $name);
                    $file[] = url('/images/' . $name);
                }
            }


            $book = Book::create([
                'name' => $data['name'],
                'price' => intval($data['price']),
                'slug' => $data['slug'],
                'quantity' => $data['quantity'],
                'category_id' => $data['category'],
                'sub_category_id' => $data['subCategory'],
                'formality_id' => $data['formality'],
                'supplier_id' => $data['suppliers'],
                'default_image' => $file[0],
            ]);
            $book->book_detail()->create([
                'book_id' => $book->id,
                'publisher' => $data['publisher'],
                'publish_year' => $data['publicDate'],
                'description' => $data['decription'],
                'author' => $data['author'],
                'page_number' => $data['pages'],
                'language' => $data['language'],
                'weight' => '220',
                'size' => '30x30x30',
            ]);
            foreach ($file as $image) {
                if ($image != $file[0]) {
                    $book->images()->create([
                        'book_id' => $book->id,
                        'image_address' => $image,
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'data_test' => $data,
                'files' => $file,
                'book' => $book->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}

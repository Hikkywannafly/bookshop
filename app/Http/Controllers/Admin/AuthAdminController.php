<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\OrderDetail;
use App\Models\OrderItem;
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
    public function update(Request $request)
    {
        try {
            $data = json_decode($request->input('data'), true);
            $book = Book::where('id', $data['id'])->first();
            $book->name = $data['name'];
            $book->price = $data['price'];
            $book->slug = $data['slug'];
            $book->quantity = $data['quantity'];
            $book->category_id = $data['category'];
            $book->sub_category_id = $data['subCategory'];
            $book->formality_id = $data['formality'];
            $book->supplier_id = $data['suppliers'];
            $book->save();


            $file = [];
            $images = $request->file('images');
            if ($request->file('images')) {
                foreach ($images as $image) {
                    $name = rand() . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path() . '/images/', $name);
                    $file[] = url('/images/' . $name);
                }
                if ($book->images) {
                    foreach ($book->images as $image) {
                        $image->delete();
                    }
                }
                foreach ($file as $image) {
                    if ($image != $file[0]) {
                        $book->images()->create([
                            'book_id' => $book->id,
                            'image_address' => $image,
                        ]);
                    }
                }
                $book->default_image = $file[0];
                $book->save();
            }


            if ($book->book_detail) {
                $book->book_detail()->update([
                    'publisher' => $data['publisher'],
                    'publish_year' => $data['publicDate'],
                    'description' => $data['decription'],
                    'author' => $data['author'],
                    'page_number' => $data['pages'],
                    'language' => $data['language'],
                    'weight' => '220',
                    'size' => '30x30x30',
                ]);
            } else {
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
            }

            return response()->json([
                'status' => 'success',
                'data_test' => $data,
                'book' => $book,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function delete(Request $request)
    {
        try {
            $book = Book::where('id', $request->id)->first();
            //    delete bookdetail images
            if ($book->book_detail) {
                $book->book_detail->delete();
            }
            if ($book->images) {
                foreach ($book->images as $image) {
                    $image->delete();
                }
            }
            $book->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function readOrder(Request $request)
    {
        $orders = OrderDetail::query()
            ->with('orderItems')
            ->with('payment')
            ->withCount([
                'orderItems as total_price' => function ($query) {
                    $query->select(DB::raw('SUM(price * quantity * (1 - discount / 100)) + 18000 '));
                },
            ])
            ->paginate(20);
        return response()->json([
            'status' => 'success',
            'orders' => $orders,

        ]);
    }

    public function readOrderDetail(Request $request)
    {

        $order = OrderDetail::query()
            ->with(
                'user',
                function ($query) {
                    $query->with('userDetail');
                }
            )
            ->with(
                'orderItems',
                function ($query) {
                    $query->with('book');
                }
            )
            ->with('payment')
            ->withCount([
                'orderItems as total_price' => function ($query) {
                    $query->select(DB::raw('SUM(price * quantity * (1 - discount / 100)) + 18000 '));
                },
            ])
            ->where('id', $request->id)
            ->first();


        return response()->json([
            'status' => 'success',
            'order' => $order,
        ]);
    }
}

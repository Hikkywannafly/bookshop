<?php

namespace App\Http\Controllers;

use App\Models\CartSesstion;
use App\Http\Requests\StoreCartSesstionRequest;
use App\Http\Requests\UpdateCartSesstionRequest;
use Illuminate\Http\Request;
use App\Models\Book;

class CartSesstionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        //

        // get items from cart sesstion and cart items 
        $cart_sesstion = CartSesstion::query()
            ->with(
                'cart_items',
            )
            ->with(
                ['cart_items.book' => function ($query) {
                    $query->select('id', 'name', 'price', 'default_image', 'discount',);
                }]

            )
            ->where('user_id', $request->user()->id)
            ->first();
        // total product
        $total_product = $cart_sesstion->cart_items->count('book_id');
        // check quantity of book in cart sesstion enough or not
        foreach ($cart_sesstion->cart_items as $cart_item) {
            $book_quantity = Book::query()->where('id', $cart_item->book_id)->first()->quantity;
            if ($book_quantity < $cart_item->quantity) {
                $cart_item->error = 'Sản phẩm này' . ' chỉ còn lại ' . $book_quantity . ' sản phẩm. Vui lòng cập nhật lại số lượng trước khi thanh toán.';
            }
        }
        return response()->json(
            [
                'status' => 'success',
                'data' => $cart_sesstion,
                'total_items' =>  $total_product
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // create cart sesstion
        $cart_sesstion = CartSesstion::query()
            ->with('user')
            ->with('cart_items')
            ->where('user_id', $request->user()->id)
            ->first();
        // check if cart sesstion is null create new cart sesstion
        if (!$cart_sesstion) {
            $cart_sesstion = CartSesstion::create([
                'user_id' => $request->user()->id,
            ]);
        }
        $cart_item = $cart_sesstion->cart_items()->where('book_id', $request->book_id)->first();
        $book_quantity = Book::query()->where('id', $request->book_id)->first()->quantity;
        // $total_items = $cart_sesstion->cart_items->sum('quantity');
        if ($book_quantity < ($cart_item->quantity ?? 0) + $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sản phẩm này chỉ còn lại ' . $book_quantity . ' sản phẩm',
            ]);
        }
        if ($cart_item) {
            $cart_item->quantity += $request->quantity;
            $cart_item->save();
        } else {
            $cart_item = $cart_sesstion->cart_items()->create([
                'book_id' => $request->book_id,
                'quantity' => $request->quantity,
                'data' => $cart_sesstion,
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $cart_sesstion,

        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCartSesstionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartSesstionRequest $request)
    {
        //


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CartSesstion  $cartSesstion
     * @return \Illuminate\Http\Response
     */
    public function show(CartSesstion $cartSesstion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CartSesstion  $cartSesstion
     * @return \Illuminate\Http\Response
     */
    public function edit(CartSesstion $cartSesstion)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCartSesstionRequest  $request
     * @param  \App\Models\CartSesstion  $cartSesstion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartSesstionRequest $request, CartSesstion $cartSesstion)
    {
        //update cart sesstion
        $cart_sesstion = CartSesstion::query()
            ->with('user')
            ->with('cart_items')
            ->where('user_id', $request->user()->id)
            ->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CartSesstion  $cartSesstion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // delete item in cart sesstion
        $cart_sesstion = CartSesstion::query()
            ->with('user')
            ->with('cart_items')
            ->where('user_id', $request->user()->id)
            ->first();
        $cart_item = $cart_sesstion->cart_items()->where('book_id', $request->book_id)->first();
        $cart_item->delete();
        return response()->json([
            'status' => 'success',
        ]);
    }
}

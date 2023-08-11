<?php

namespace App\Services;

use App\Models\Cart;
use Exception;
use Illuminate\Http\Response;

class CartService
{
    const LIMIT = 20;

    protected $cart;
    public function __construct()
    {
        $this->cart = new Cart;
    }

    public function addToCart($request)
    {
        $cart = $this->cart->create($request);

        return $cart;
    }

    public function getCartItems($userId)
    {
        $carts = $this->cart->where('user_id', $userId)->get();

        return $carts;
    }

    public function deleteCartItems($request)
    {
        $cart = $this->cart->where('user_id', $request['user_id'])->where('product_id', $request['product_id'])->first();

        if (!$cart) {
            throw new Exception('Item not found in cart', Response::HTTP_NOT_FOUND);
        }

        return $cart->delete();
    }

}

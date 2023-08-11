<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelpers;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Services\CartService;
use Illuminate\Http\Response;
use Throwable;

class CartController extends Controller
{
    private $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(CartRequest $request)
    {
        try {
            $data = new CartResource($this->cartService->addToCart($request->validated()));
            return response()->json(['status' => Response::HTTP_CREATED, 'data' => $data, 'message' => 'Cart fetched successfully'], Response::HTTP_CREATED);
        } catch (Throwable $ex) {
            $statusCode = GeneralHelpers::checkStatusCode($ex->getCode());
            return response()->json(['status' => $statusCode, 'data' => null, 'message' => $ex->getMessage()], $statusCode);
        }
    }

    public function getCartItems()
    {
        try {
            $userId = auth()->id();
            $data = CartResource::collection($this->cartService->getCartItems($userId));
            return response()->json(['status' => Response::HTTP_OK, 'data' => $data, 'message' => 'Cart fetched successfully'], Response::HTTP_OK);
        } catch (Throwable $ex) {
            $statusCode = GeneralHelpers::checkStatusCode($ex->getCode());
            return response()->json(['status' => $statusCode, 'data' => null, 'message' => $ex->getMessage()], $statusCode);
        }
    }

    public function deleteCartItems(CartRequest $request)
    {
        try {
            $data = $this->cartService->deleteCartItems($request->validated());
            return response()->json(['status' => Response::HTTP_OK, 'data' => null, 'message' => 'Item removed from cart successfully'], Response::HTTP_OK);
        } catch (Throwable $ex) {
            $statusCode = GeneralHelpers::checkStatusCode($ex->getCode());
            return response()->json(['status' => $statusCode, 'data' => null, 'message' => $ex->getMessage()], $statusCode);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelpers;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class ProductController extends Controller
{
    private $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userId = auth()->id();
            $data = ProductResource::collection($this->productService->index());
            return response()->json(['status' => Response::HTTP_OK, 'data' => $data, 'message' => 'Product fetched successfully'], Response::HTTP_OK);
        } catch (Throwable $ex) {
            $statusCode = GeneralHelpers::checkStatusCode($ex->getCode());
            return response()->json(['status' => $statusCode, 'data' => null, 'message' => $ex->getMessage()], $statusCode);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $data = new ProductResource($this->productService->create($request));
            return response()->json(['status' => Response::HTTP_CREATED, 'data' => $data, 'message' => 'Product created successfully'], Response::HTTP_CREATED);
        } catch (Throwable $ex) {
            $statusCode = GeneralHelpers::checkStatusCode($ex->getCode());
            return response()->json(['status' => $statusCode, 'data' => null, 'message' => $ex->getMessage()], $statusCode);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
            $data = new ProductResource($this->productService->show($request));
            return response()->json(['status' => Response::HTTP_OK, 'data' => $data, 'message' => 'Product fetched successfully'], Response::HTTP_OK);
        } catch (Throwable $ex) {
            $statusCode = GeneralHelpers::checkStatusCode($ex->getCode());
            return response()->json(['status' => $statusCode, 'data' => null, 'message' => $ex->getMessage()], $statusCode);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request)
    {
        try {
            $data = new ProductResource($this->productService->update($request));
            return response()->json(['status' => Response::HTTP_OK, 'data' => $data, 'message' => 'Product updated successfully'], Response::HTTP_OK);
        } catch (Throwable $ex) {
            $statusCode = GeneralHelpers::checkStatusCode($ex->getCode());
            return response()->json(['status' => $statusCode, 'data' => null, 'message' => $ex->getMessage()], $statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $data = $this->productService->delete($request);
            return response()->json(['status' => Response::HTTP_OK, 'data' => null, 'message' => 'Product deleted successfully'], Response::HTTP_OK);
        } catch (Throwable $ex) {
            $statusCode = GeneralHelpers::checkStatusCode($ex->getCode());
            return response()->json(['status' => $statusCode, 'data' => null, 'message' => $ex->getMessage()], $statusCode);
        }
    }
}

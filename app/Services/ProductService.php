<?php

namespace App\Services;

use App\Models\Product;
use Exception;
use Illuminate\Http\Response;

class ProductService
{

    const LIMIT = 20;

    protected $product;

    public function __construct()
    {

        $this->product = new Product;

    }

    public function index()
    {

        $products = $this->product->paginate(self::LIMIT);

        return $products;

    }

    public function create($request)
    {

        $product = $this->product->where('name', $request['name'])->first();

        if ($product) {

            throw new Exception('Product already exists', Response::HTTP_BAD_REQUEST);

        }

        return $this->product->create([

            'name' => $request['name'],

            'price' => $request['price'],

            'description' => $request['description'] ?? null,

            'user_id' => $request['user_id'],

            'img_path' => $request['img_path'],

        ]);

    }

    public function show($request)
    {

        $product = $this->product->where('id', $request->id)->first();

        if (!$product) {

            throw new Exception('Product not found', Response::HTTP_NOT_FOUND);

        }

        return $product;

    }

    public function update($request)
    {

        $product = $this->product->where('id', $request->id)->first();

        if (!$product) {

            throw new Exception('Product not found', Response::HTTP_NOT_FOUND);

        }

        $product->update([

            'name' => $request->name ?? $product->name,

            'price' => $request->price ?? $product->price,

            'description' => $request->description ?? $product->description,

            'img_path' => $request->img_path ?? $product->img_path,

        ]);

        return $product;

    }

    public function delete($request)
    {

        $product = $this->product->where('id', $request->id)->first();

        if (!$product) {

            throw new Exception('Product not found', Response::HTTP_NOT_FOUND);

        }

        return $product->delete();

    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $search = request()->query('search');

        $products = Product::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->paginate(15);

        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());
        return response()->json($product, 201);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());
        return response()->json($product);
    }

}
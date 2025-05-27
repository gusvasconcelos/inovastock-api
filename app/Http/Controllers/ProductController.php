<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\Product\ProductService;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    public function index(): JsonResponse
    {
        $products = $this->productService->index();

        return response()->json($products);
    }

    public function all(): JsonResponse
    {
        $data = $this->productService->all();

        return response()->json($data);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->show($id);

        return response()->json($product);
    }

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $product = $this->productService->store(collect($validated));

        return response()->json($product, 201);
    }

    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $product = $this->productService->update(collect($validated), $id);

        return response()->json($product);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productService->destroy($id);

        return response()->json(['message' => 'Produto deletado com sucesso.']);
    }
}
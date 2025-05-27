<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(
        protected Product $product
    ) {
    }

    public function index(): LengthAwarePaginator
    {
        return $this->product->paginate(15);
    }

    public function all(): Collection
    {
        return $this->product->get();
    }

    public function show(int $id): Product
    {
        return $this->product->findOrFail($id);
    }

    public function store(Collection $data): Product
    {
        return $this->product->create($data->toArray());
    }

    public function update(Collection $data, int $id): Product
    {
        $product = $this->product->findOrFail($id);

        $product->update($data->toArray());

        return $product->refresh();
    }

    public function destroy(int $id): bool
    {
        return $this->product->findOrFail($id)->delete();
    }
}

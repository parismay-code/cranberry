<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\ExtraCategory;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    public function get(Request $request): Response
    {
        return response(['products' => ProductResource::collection(Product::all())]);
    }

    public function create(ProductRequest $request, Category $category): Response
    {
        $data = $request->validated();

        /** @var Product $product */
        $product = $category->products()->create(['name' => $data['name'], 'price' => $data['price']]);

        if (!empty($data['extra_category_id'])) {
            $extraCategory = ExtraCategory::query()->find($data['extra_category_id']);

            $product->extraCategory()->associate($extraCategory);
        }

        return response(['product' => $product]);
    }

    public function update(ProductRequest $request, Product $product): Response
    {
        $data = $request->validated();

        $product->update(['name' => $data['name'], 'price' => $data['price']]);

        if ($product->extraCategory()->exists()) {
            $product->extraCategory()->dissociate();
        }

        if (!empty($data['extra_category_id'])) {
            $extraCategory = ExtraCategory::query()->find($data['extra_category_id']);

            $product->extraCategory()->associate($extraCategory);
        }

        return response(['product' => new ProductResource($product)]);
    }

    public function addExtra(Request $request, Product $product, ExtraCategory $extraCategory): Response
    {
        if ($product->extraCategory()->exists()) {
            $product->extraCategory()->dissociate();
        }

        $product->extraCategory()->associate($extraCategory);

        return response(['product' => $product]);
    }

    public function removeExtra(Request $request, Product $product): Response
    {
        if ($product->extraCategory()) {
            $product->extraCategory()->dissociate();
        }

        return response(['product' => $product]);
    }

    public function addIngredient(Request $request, Product $product, Ingredient $ingredient): Response
    {
        $product->ingredients()->attach($ingredient->id);

        return response(['product' => $product]);
    }

    public function removeIngredient(Request $request, Product $product, Ingredient $ingredient): Response
    {
        $product->ingredients()->detach($ingredient->id);

        return response(['product' => $product]);
    }

    public function delete(Request $request, Product $product): Response
    {
        $status = $product->delete();

        if (!$status) {
            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response();
    }
}

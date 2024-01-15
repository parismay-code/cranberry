<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\ExtraCategory;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    public function get(Request $request): Response
    {
        return response(['products' => ProductResource::collection(Product::all())]);
    }

    public function create(ProductRequest $request, Category $category): Response
    {
        $data = $request->safe()->except('extra_category_id');

        $excludedData = $request->safe()->only('extra_category_id');

        /** @var Product $product */
        $product = $category->products()->create($data);

        if (!empty($excludedData)) {
            $extraCategory = ExtraCategory::query()->find($excludedData['extra_category_id']);

            $product->extraCategory()->associate($extraCategory);
        }

        return response(['product' => new ProductResource($product)]);
    }

    public function update(ProductRequest $request, Product $product): Response
    {
        $data = $request->safe()->except('extra_category_id');

        $excludedData = $request->safe()->only('extra_category_id');

        $product->update($data);

        if ($product->extraCategory()->exists()) {
            $product->extraCategory()->dissociate();
        }

        if (!empty($excludedData)) {
            $extraCategory = ExtraCategory::query()->find($excludedData['extra_category_id']);

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

        return response(['product' => new ProductResource($product)]);
    }

    public function removeExtra(Request $request, Product $product): Response
    {
        if ($product->extraCategory()) {
            $product->extraCategory()->dissociate();
        }

        return response(['product' => new ProductResource($product)]);
    }

    public function addIngredient(Request $request, Product $product, Ingredient $ingredient): Response
    {
        $count = $request->post('count') ?? 1;

        if ($product->ingredients()->where('ingredient_id', $ingredient->id)->exists()) {
            $product->ingredients()->detach($ingredient->id);
        }

        for ($i = 0; $i < $count; $i++) {
            $product->ingredients()->attach($ingredient->id);
        }

        return response(['product' => new ProductResource($product)]);
    }

    public function removeIngredient(Request $request, Product $product, Ingredient $ingredient): Response
    {
        $product->ingredients()->detach($ingredient->id);

        return response(['product' => new ProductResource($product)]);
    }

    public function delete(Request $request, Product $product): Response
    {
        $status = $product->delete();

        if (!$status) {
            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response('ok');
    }
}

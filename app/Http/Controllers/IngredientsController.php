<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngredientRequest;
use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IngredientsController extends Controller
{
    public function get(Request $request): Response
    {
        return response(['ingredients' => IngredientResource::collection(Ingredient::all())]);
    }

    public function create(IngredientRequest $request): Response
    {
        $data = $request->validated();

        $ingredient = Ingredient::query()->create($data);

        return response(['ingredient' => new IngredientResource($ingredient)]);
    }

    public function update(IngredientRequest $request, Ingredient $ingredient): Response
    {
        $data = $request->validated();

        $ingredient->update($data);

        return response(['ingredient' => new IngredientResource($ingredient)]);
    }

    public function delete(Request $request, Ingredient $ingredient): Response
    {
        $status = $ingredient->delete();

        if (!$status) {
            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response();
    }
}

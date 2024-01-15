<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    public function get(Request $request): Response
    {
        return response(['categories' => CategoryResource::collection(Category::all())]);
    }

    public function create(CategoryRequest $request): Response
    {
        $data = $request->validated();

        $category = Category::query()->create($data);

        return response(['category' => new CategoryResource($category)]);
    }

    public function update(CategoryRequest $request, Category $category): Response
    {
        $data = $request->validated();

        $category->update($data);

        return response(['category' => new CategoryResource($category)]);
    }

    public function delete(Request $request, Category $category): Response
    {
        $status = $category->delete();

        if (!$status) {
            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response('ok');
    }
}

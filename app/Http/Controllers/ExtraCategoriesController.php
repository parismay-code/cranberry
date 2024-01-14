<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExtraCategoryRequest;
use App\Http\Resources\ExtraCategoryResource;
use App\Models\ExtraCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExtraCategoriesController extends Controller
{
    public function get(Request $request): Response
    {
        return response(['extraCategories' => ExtraCategoryResource::collection(ExtraCategory::all())]);
    }

    public function create(ExtraCategoryRequest $request): Response
    {
        $data = $request->validated();

        $extraCategory = ExtraCategory::query()->create($data);

        return response(['extraCategory' => new ExtraCategoryResource($extraCategory)]);
    }

    public function update(ExtraCategoryRequest $request, ExtraCategory $extraCategory): Response
    {
        $data = $request->validated();

        $extraCategory->update($data);

        return response(['extraCategory' => new ExtraCategoryResource($extraCategory)]);
    }

    public function delete(Request $request, ExtraCategory $extraCategory): Response
    {
        $status = $extraCategory->delete();

        if (!$status) {
            return response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response();
    }
}

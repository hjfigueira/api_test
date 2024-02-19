<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\ApiController;
use App\Http\Filters\Core\ApiFilterable;
use App\Http\Interfaces\ApiFilterableController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FundDuplicatesApiController extends ApiController implements ApiFilterableController
{
    use ApiFilterable;

    public function getFilters() : array
    {
        return [
            'parent_id'    => [
                'equal'
            ],
            'resolved'      => [
                'equal'
            ]
        ];
    }

    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function destroy(Request $request, int $id): JsonResponse
    {
        return new JsonResponse(['message' => 'not available'], 404);
    }

    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function store(Request $request): JsonResponse
    {
        return new JsonResponse(['message' => 'not available'], 404);
    }

    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function update(Request $request, int $id): JsonResponse
    {
        return new JsonResponse(['message' => 'not available'], 404);
    }
}

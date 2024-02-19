<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FundDuplicatesApiController extends ApiController
{
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

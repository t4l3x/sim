<?php
declare(strict_types=1);

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;

class ApiHelpers
{
    public static function successResponse(string $message, array $data = []): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ]);
    }

    public static function errorResponse(string $message, int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }
}

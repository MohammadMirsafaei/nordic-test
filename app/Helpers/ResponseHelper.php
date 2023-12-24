<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function error(string $message, int $status = 500): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $status);
    }

    public static function serverInternalError(): JsonResponse
    {
        return self::error("Server internal error", 500);
    }

    public static function notFoundError(string $message): JsonResponse
    {
        return self::error($message, 404);
    }
}

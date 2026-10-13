<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(string $message, mixed $data = null, mixed $meta = null, int $status = 200): JsonResponse
    {
        return response()->json(['success' => true, 'message' => $message, 'data' => $data, 'errors' => null, 'meta' => $meta], $status);
    }

    public static function created(string $message, mixed $data = null): JsonResponse
    {
        return self::success($message, $data, null, 201);
    }

    public static function error(string $message, int $status = 500, mixed $errors = null): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $message, 'data' => null, 'errors' => $errors, 'meta' => null], $status);
    }

    public static function notFound(string $message = 'Data tidak ditemukan.'): JsonResponse
    {
        return self::error($message, 404);
    }
}

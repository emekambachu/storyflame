<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * @param $message
     * @param $data
     * @return JsonResponse
     */
    public function successResponse($message, $data = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'type' => 'success',
        ]);
    }

    /**
     * @param $message
     * @param $code
     * @param $type
     * @param $details
     * @param $errors
     * @return JsonResponse
     */
    public function errorResponse($message, $code, $type = 'error', $details = null, $errors = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'details' => $details,
            'type' => $type,
            'errors' => $errors,
            'data' => null,
        ], $code);
    }
}

<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function successResponse($message, $data = null)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'type' => 'success',
        ]);
    }

    public function errorResponse($message, $code, $type = 'error', $details = null)
    {
        return response()->json([
            'message' => $message,
            'details' => $details,
            'type' => $type,
        ], $code);
    }
}

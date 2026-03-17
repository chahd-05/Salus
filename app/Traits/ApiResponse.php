<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function success($data = null, $message = "Opération réussie", $code = 200): JsonResponse
    {
        return response()->json([
            "success" => true,
            "data" => $data,
            "message" => $message
        ], $code);
    }

    public function error($errors = null, $message = "Erreur", $code = 400): JsonResponse
    {
        return response()->json([
            "success" => false,
            "errors" => $errors,
            "message" => $message
        ], $code);
    }
}
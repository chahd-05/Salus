<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SwaggerTestConctoller extends Controller
{
    #[OA\Get(
        path: "/test",
        summary: "test endpoint",
        tags: ["test"],
        responses: [
            new OA\Response(response: 200, description: "ok")
        ]
    )

    ]
    public function index() {
        return "swagger test";
    }
}

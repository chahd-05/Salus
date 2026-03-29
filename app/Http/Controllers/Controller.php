<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;


    #[OA\Info(
        title: "salus documentation",
        version: "1.0.0"
    )]
    #[OA\SecurityScheme(
        securityScheme: 'sunctum',
        type: 'http',
        scheme: 'bearer',
        bearerFormat: 'JWT'
    )]

    #[OA\server(
        url: '/api',
    )]
abstract class Controller
{

}

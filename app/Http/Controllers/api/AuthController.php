<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations\RequestBody;
use OpenApi\Attributes as OA;
use Symfony\Contracts\Service\Attribute\Required;

class AuthController extends Controller
{
   #[OA\Post(
        path: '/register',
        summary: 'Create a new account',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string' , example: 'chahd'),
                    new OA\Property(property: 'email', type: 'string', example: 'chahd@gmail.com' ),
                    new OA\Property(property: 'password', type: 'string'),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'user created'),
        ]
    )]

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => 'required',
            'email' => ['required', 'unique:users,email'],
            'password' => 'required'
        ]);
        $user = User::create($incomingFields);
        $token = $user->createToken('myToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    #[OA\Post(
        path: '/login',
        summary: 'logged in',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'chahd@gmail.com' ),
                    new OA\Property(property: 'password', type: 'string'),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'user created'),
        ]
    )]

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        if (auth()->attempt($incomingFields)) {
            $token = auth()->user()->createToken('myToken')->plainTextToken;
            return [
                'message' => 'login was a success',
                'user' => auth()->user(),
                'token' => $token
            ];
        } else {
            return [
                'message' => 'check your credentiels'
            ];
        }
    }

    #[OA\Post(
        path: '/logout',
        security: [['sanctum' => []]],
        summary: 'logged out',
        tags: ['Auth'],
    
        responses: [
            new OA\Response(response: 201, description: 'user created'),
        ]
    )]

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'You have logged out'
        ];
    }
}

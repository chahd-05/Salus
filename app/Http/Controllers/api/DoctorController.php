<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DoctorController extends Controller
{
    use ApiResponse;

     #[OA\Get(
        path: '/doctors',
        summary: 'Get all doctors',
        security: [['sanctum' => []]],
        tags: ['doctors'],
        responses: [
            new OA\Response(response: 200, description: 'List of doctors'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'doctors not found'),
        ]
    )]

    public function index() {
        $doctors = Doctor::All();
        return $this->success($doctors, "doctors list");
    }

    #[OA\Get(
        path: '/doctors/{id}',
        summary: 'Get all doctors',
        security: [['sanctum' => []]],
        tags: ['doctors'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'fetch doctor'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'doctors not found'),
        ]
    )]

    public function show($id) {
        $doctor = Doctor::find($id);
        if(!$doctor){
            return $this->error(null, "doctor not found");
        }
        return $this->success($doctor, "doctor details");
    }

     #[OA\Get(
        path: '/doctors/search',
        summary: 'search for doctors',
        security: [['sanctum' => []]],
        tags: ['doctors'],
        parameters: [
                    new OA\Parameter(name: 'specialty', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: "General Medicine")),
                    new OA\Parameter(name: 'city', in: 'query', required: false, schema: new OA\Schema(type: 'string', example: "rabat")),
                ],
        responses: [
            new OA\Response(response: 200, description: 'List of doctors'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'doctors not found'),
        ]
    )]
    
    public function search(Request $request){
        $query = Doctor::query();

        if($request->specialty){
            $query->where('specialty', 'like', '%' .$request->specialty. '%');
        }

        if($request->city){
            $query->where('city', 'like', '%' .$request->city .'%');
        }
        $doctors = $query->get();
        return $this->success($doctors, "search results");
    }
}

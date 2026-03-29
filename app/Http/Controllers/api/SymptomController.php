<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSymptomRequest;
use App\Models\Symptom;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use OpenApi\Attributes as OA;

class SymptomController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */

    #[OA\Get(
        path: '/symptoms',
        summary: 'Get all symptoms for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]

    public function index()
    {
        $symptoms = auth()->user()->symptoms;
        return $this->success($symptoms, "list of symptoms");
    }

    /**
     * Store a newly created resource in storage.
     */

     #[OA\Post(
        path: '/symptoms',
        summary: 'Create a new symptom',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'severity', 'date_recorded'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'itches'),
                    new OA\Property(property: 'severity', type: 'string', enum:['mild', 'moderate', 'severe'], example: 'mild'),
                    new OA\Property(property: 'description', type: 'string', example: '.....'),
                    new OA\Property(property: 'date_recorded', type: 'string', format:'date' , example: '2020-03-12'),
                    new OA\Property(
                    property: 'notes',
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        example: 'I carried it for a week, it gets stronger'
                    )
                ),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'Symptom created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]

    public function store(StoreSymptomRequest $request)
    {

        $sypmtom = Symptom::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'severity' => $request->severity,
            'description' => $request->description,
            'note' => $request->note,
            'dateRecorded' => $request->dateRecorded
        ]);

        return $this->success($sypmtom, "symptom added");
    }

    /**
     * Display the specified resource.
     */

    #[OA\Get(
        path: '/symptoms/{symptom}',
        summary: 'Get one symptoms for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        parameters: [
            new OA\Parameter(name: 'symptom', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]

    public function show(string $id)
    {
        $symptom = Symptom::find($id);
        
        if(!$symptom){
            return $this->error(null, "symptom not found");
        }
        return $this->success($symptom, "symptom details");
    }

    /**
     * Update the specified resource in storage.
     */

    #[OA\Put(
        path: '/symptoms/{symptom}',
        summary: 'Update a symptom',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        parameters: [
            new OA\Parameter(name: 'symptom', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'itches'),
                    new OA\Property(property: 'severity', type: 'string', enum:['mild', 'moderate', 'severe'], example: 'mild'),
                    new OA\Property(property: 'description', type: 'string', example: '.....'),
                    new OA\Property(property: 'date_recorded', type: 'string', format:'date' , example: '2020-03-12'),
                    new OA\Property(
                    property: 'notes',
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        example: 'I carried it for a week, it gets stronger'
                    )
                ),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'Symptom Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]

    public function update(StoreSymptomRequest $request, string $id)
    {
        $symptom = Symptom::find($id);

        if(!$symptom){
            return $this->error(null, "symptom not found");
        }
        $symptom->update($request->validated());

        return $this->success($symptom, "symptom updated");
    }

    /**
     * Remove the specified resource from storage.
     */

     #[OA\Delete(
        path: '/symptoms/{symptom}',
        summary: 'Get one symptoms for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['Symptoms'],
        parameters: [
            new OA\Parameter(name: 'symptom', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]

    public function destroy(string $id)
    {
        $symptom = Symptom::find($id);
        if(!$symptom){
            return $this->error(null, "symptom not found");
        }
        $symptom->delete();
        return $this->success(null, "symptom deleted");
    }

     #[OA\Post(
        path: '/ai/health-advice',
        summary: 'Get ai Health advice',
        security: [['sanctum' => []]],
        tags: ['AI'],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]
    
    public function ai() {
            $symptom = auth()->user()->symptoms()->latest()->first();

            $respond = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key= " . env('GEMINI_API_KEY'), [
                "contents" => [
                    [
                        "parts" => [
                            [
                                "text" => $symptom . "what should i do to lower the pain just an advice 1 sentence"
                            ]
                        ]
                    ]
                ]
            ]);
            if($respond->successful()){
                $output = $respond->json()['candidates'][0]['content']['parts'][0]['text'];
            }
            else{
                $output = 'error';
        }
        return response()->json([
            'success' => true,
            'symptom' => $symptom,
            'response' => $output
        ]);
    }
}

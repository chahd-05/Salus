<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AppointmentController extends Controller
{
    use ApiResponse;
   
     #[OA\Get(
        path: '/appointments',
        summary: 'Get all appointments for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        responses: [
            new OA\Response(response: 200, description: 'List of user appointments'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'appointments not found'),
        ]
    )]

    public function index()
    {
        $appointments = auth()->user->appointments()->with('doctor')->get();
        return $this->success($appointments, "appointment list");
    }

    /**
     * Store a newly created resource in storage.
     */

      #[OA\Post(
        path: '/appointments',
        summary: 'Create a new appointment',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['appointment_date', 'doctor_id'],
                properties: [
                    new OA\Property(property: 'appointment_date', type: 'string', format:'date' , example: '2020-03-12'),
                    new OA\Property(property: 'status', type: 'string', enum:['pending','confirmed','cancelled'], example: 'pending or confirmed' ),
                    new OA\Property(property: 'doctor_id', type: 'integer' , example: '1'),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'Symptom created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exist:doctors, id',
            'appointment_date' => 'required|date|after:today',
            'note' => 'nullable|string'
        ]);

        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'status' => 'pending',
            'note' => $request->notes
        ]);
        return $this->success($appointment, "appointment created");
    }

    /**
     * Display the specified resource.
     */

      #[OA\Get(
        path: '/appointments/{appointment}',
        summary: 'Get one appointments for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        parameters: [
            new OA\Parameter(name: 'appointment', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of user appointment'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'appointment not found'),
        ]
    )]

    public function show(string $id)
    {
        $appointment = auth()->user()->appointments()->with('doctor')->find($id);
        if(!$appointment){
            return $this->error(null, 'appointment not found');
        }
        return $this->success($appointment, "appointment details");
    }

    /**
     * Update the specified resource in storage.
     */

     #[OA\Put(
        path: '/appointments',
        summary: 'Update appointment',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        parameters: [
            new OA\Parameter(name: 'appointment', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['appointment_date', 'doctor_id'],
                properties: [
                    new OA\Property(property: 'appointment_date', type: 'string', format:'date' , example: '2020-03-12'),
                    new OA\Property(property: 'status', type: 'string', enum:['pending','confirmed','cancelled'], example: 'pending or confirmed' ),
                    new OA\Property(property: 'doctor_id', type: 'integer' , example: '1'),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'appointment Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]

    public function update(Request $request, string $id)
    {
        $appointment = auth()->user()->appointment()->find($id);
        if(!$appointment){
            return $this->error(null, "appointment not found");
        }
        $request->validate([
            'appointment_date' => 'nullable|date|after:today',
            'status' => 'nullable|in:pending,confirmed, cancelled',
            'note' =>'nullable|string'
        ]);
        $appointment->update($request->only('appointment_date', 'status', 'note'));
        return $this->success($appointment, "appointment updated");
    }

    /**
     * Remove the specified resource from storage.
     */

    #[OA\Delete(
        path: '/appointments/{appointment}',
        summary: 'Delete appointments for the authenticated user',
        security: [['sanctum' => []]],
        tags: ['appointments'],
        parameters: [
            new OA\Parameter(name: 'appointment', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of user appointment'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'appointment not found'),
        ]
    )]
    
    public function destroy(string $id)
    {
        $appointment = auth()->user()->appointments()->find($id);
        if($appointment){
            return $this->error(null, "appointment not found");
        }
        
        $appointment->delete();

        return $this->success(null, "appointment deleted");
    }
}

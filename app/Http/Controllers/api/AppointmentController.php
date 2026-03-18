<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = auth()->user->appointments()->with('doctor')->get();
        return $this->success($appointments, "appointment list");
    }

    /**
     * Store a newly created resource in storage.
     */
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

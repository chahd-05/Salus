<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    use ApiResponse;

    public function index() {
        $doctors = Doctor::All();
        return $this->success($doctors, "doctors list");
    }

    public function show($id) {
        $doctor = Doctor::find($id);
        if(!$doctor){
            return $this->error(null, "doctor not found");
        }
        return $this->success($doctor, "doctor details");
    }

    public function search(Request $request){
        $query = Doctor::query();

        if($request->has('specialty')){
            $query->where('specialty', 'like', '%' .$request->specialty. '%');
        }

        if($request->has('city')){
            $query->where('city', 'like', '%' .$request->city .'%');
        }
        $doctors = $query->get();
        return $this->success($doctors, "search results");
    }
}

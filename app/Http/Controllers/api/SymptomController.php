<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSymptomRequest;
use App\Models\Symptom;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $symptoms = auth()->user()->symptoms;
        return $this->success($symptoms, "list of symptoms");
    }

    /**
     * Store a newly created resource in storage.
     */
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
    public function destroy(string $id)
    {
        $symptom = Symptom::find($id);
        if(!$symptom){
            return $this->error(null, "symptom not found");
        }
        $symptom->delete();
        return $this->success(null, "symptom deleted");
    }
}

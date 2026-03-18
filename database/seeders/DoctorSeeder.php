<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Doctor::create([
            'doctor_name' => 'Dr Ahmed',
            'specialty' => 'Cardiologist',
            'city' => 'casablanca',
            'yearsOfExperience' => 10,
            'consultationPrice' => 300,
            'availableDays' => 'Mon,Tue,Wed'
        ]);
        Doctor::create([
            'doctor_name' => 'Dr sara',
            'specialty' => 'dermatologist',
            'city' => 'rabat',
            'yearsOfExperience' => 7,
            'consultationPrice' => 250,
            'availableDays' => 'Tue,Thu'
        ]);
        Doctor::create([
            'doctor_name' => 'Dr hamza',
            'specialty' => 'general practitioner',
            'city' => 'casablanca',
            'yearsOfExperience' => 8,
            'consultationPrice' => 150,
            'availableDays' => 'Mon,Wed'
        ]);
    }
}

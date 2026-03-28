<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        Doctor::create([
            'name' => 'Dr. Ahmed Salem',
            'specialty' => 'Cardiology',
            'city' => 'Casablanca',
            'years_of_experience' => 10,
            'consultation_price' => 300.00,
            'available_days' => ['Monday', 'Wednesday', 'Friday']
        ]);

        Doctor::create([
            'name' => 'Dr. Sara Karim',
            'specialty' => 'Dermatology',
            'city' => 'Rabat',
            'years_of_experience' => 5,
            'consultation_price' => 250.00,
            'available_days' => ['Tuesday', 'Thursday']
        ]);
    }
}

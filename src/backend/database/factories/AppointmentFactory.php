<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\TimeInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date_appointment' => $this->faker->date('Y-m-d'),
            'time_interval_id'  => TimeInterval::get()->random()->id,
            'doctor_id' => Doctor::get()->random()->id,
            'patient_id' => Patient::get()->random()->id,
        ];
    }
}

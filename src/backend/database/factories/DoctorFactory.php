<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'last_name' => $this->faker->lastName,
            'first_name'  => $this->faker->firstName,
            'middle_name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'start_work_day' => '08:00',
            'end_work_day' => '14:00',
            'birthday' => $this->faker->date('Y-m-d'),
            'email' => $this->faker->email,
        ];
    }
}

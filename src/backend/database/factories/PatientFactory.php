<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;
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
            'legNP' => random_int(11111111111, 99999999999),
            'birthday' => $this->faker->date('Y-m-d'),
            'place_of_residence' => $this->faker->address,
        ];

    }
}

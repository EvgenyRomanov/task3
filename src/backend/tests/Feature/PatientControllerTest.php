<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class PatientControllerTest extends TestCase
{
    public function testPatientIsCreatedSuccessfully()
    {
        $payload = [
            'last_name' => 'Test',
            'first_name'  => 'Test',
            'middle_name' => 'Test',
            'legNP' => '12345678911',
            'birthday' => '1990-10-10',
            'place_of_residence' => 'Москва',
        ];

        $this->json('post', 'api/patients', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'last_name',
                        'middle_name',
                        'legNP',
                        'birthday',
                        'place_of_residence',
                    ]
                ]
            );
        $this->assertDatabaseHas('patients', $payload);
    }

    public function testPatientIsCreatedFail()
    {
        $payload = [
            'last_name' => 'Test',
            'first_name'  => 'Test',
            'middle_name' => 'Test',
            'legNP' => '123456789',  // некорректный номер СНИЛС
            'birthday' => '1990-10-10',
            'place_of_residence' => 'Москва',
        ];

        $this->json('post', 'api/patients', $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'legNP'
                ]
            );
    }
}

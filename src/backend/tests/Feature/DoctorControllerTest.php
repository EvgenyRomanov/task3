<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;


class DoctorControllerTest extends TestCase
{
    public function testDoctorIsCreatedSuccessfully()
    {
        $payload = [
            'last_name' => 'Test',
            'first_name'  => 'Test',
            'middle_name' => 'Test',
            'phone' => '+7 123 456 78 90',
            'start_work_day' => '08:00',
            'end_work_day' => '18:00',
            'birthday' => '1990-10-10',
            'email' => 'qwert@mail.com',
        ];

        $this->json('post', 'api/doctors', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'last_name',
                        'middle_name',
                        'phone',
                        'email',
                        'start_work_day',
                        'end_work_day',
                        'birthday',
                        'email'
                    ]
                ]
            );
        $this->assertDatabaseHas('doctors', $payload);
    }

    public function testDoctorIsCreatedFail()
    {
        $payload = [
            'last_name' => 'Test',
            'first_name'  => 'Test',
            'middle_name' => 'Test',
            'phone' => '+7 123 456 78 90',
            'start_work_day' => '08:00',
            'end_work_day' => '18:00',
            'birthday' => '1990-10', // ошибка формата
            'email' => 'qwert@mail.com',
        ];

        $this->json('post', 'api/doctors', $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'birthday'
                ]
            );
    }
}

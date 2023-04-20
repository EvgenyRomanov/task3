<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class AppointmentControllerTest extends TestCase
{
    public function testIndexReturnsDataInValidFormat()
    {
        Artisan::call('db:seed --class=DatabaseSeeder');

        $this->json('get', 'api/appointments')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'date_appointment',
                            'time_interval_id',
                            'doctor_id',
                            'patient_id',
                        ]
                    ]
                ]
            );
    }

    public function testIndexReturnsDataInValidFormatFilter()
    {
        Artisan::call('db:seed --class=DatabaseSeeder');

        $this->json('get', 'api/appointments?doc_first_name=test')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'date_appointment',
                            'time_interval_id',
                            'doctor_id',
                            'patient_id',
                        ]
                    ]
                ]
            );
    }

    public function testIndexReturnsDataInValidFormatFilterFail()
    {
        Artisan::call('db:seed --class=DatabaseSeeder');

        $this->json('get', 'api/appointments?start_date=test')
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'start_date'
                ]
            );
    }

    public function testAppointmentIsCreatedSuccessfully()
    {
        Artisan::call('db:seed --class=DatabaseSeederTest');

        // 10 докторов, у каждого их которых 3 записи в течение дня
        // и 10 пациоентов
        // записи непересекаются, за границы рабочего дня не выходят
        for ($i = 1; $i <= 3; $i++) {
            for ($j = 1; $j <= 10; $j++) {
                $payload = [
                    'date_appointment' => "2020-10-10",
                    'time_interval_id'  => $i + 4,         // протестируем интервалы начиная с id=5
                    'doctor_id' => $j,
                    'patient_id' => $j,
                ];

                $this->json('post', 'api/appointments', $payload)
                    ->assertStatus(Response::HTTP_CREATED)
                    ->assertJsonStructure(
                        [
                            'data' => [
                                'date_appointment',
                                'time_interval_id',
                                'doctor_id',
                                'patient_id',
                            ]
                        ]
                    );
                $this->assertDatabaseHas('appointments', $payload);
            }
        }
    }

    // пересекаются интервалы записей
    public function testAppointmentIsCreatedFailCrossInterval()
    {
        Artisan::call('db:seed --class=DatabaseSeederTest');

        $payload1 = [
            'date_appointment' => "2020-10-10",
            'time_interval_id'  => 5,
            'doctor_id' => 1,
            'patient_id' => 1,
        ];

        $payload2 = [
            'date_appointment' => "2020-10-10",
            'time_interval_id'  => 5,
            'doctor_id' => 1,
            'patient_id' => 1,
        ];

        $this->json('post', 'api/appointments', $payload1)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'date_appointment',
                        'time_interval_id',
                        'doctor_id',
                        'patient_id',
                    ]
                ]
            );
        $this->assertDatabaseHas('appointments', $payload1);


        $this->json('post', 'api/appointments', $payload2)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'time_interval_id'
                ]
            );
    }

    // выходит за границы рабочего дня
    public function testAppointmentIsCreatedFailAbroadWorkDay()
    {
        Artisan::call('db:seed --class=DatabaseSeederTest');

        $payload = [
            'date_appointment' => "2020-10-10",
            'time_interval_id'  => 1,
            'doctor_id' => 1,
            'patient_id' => 1,
        ];

        $this->json('post', 'api/appointments', $payload)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'time_interval_id'
                ]
            );
    }
}

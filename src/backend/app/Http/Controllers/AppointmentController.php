<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\TimeInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentController extends Controller
{
    public const TIME_INTERVAL = 30;
    public const PER_PAGE = 10;

    public function store(Request $request)
    {
        $data = $request->all();

        $rules = [
            'time_interval_id' => function ($attribute, $value, $fail) use ($data) {
                $doctor = Doctor::find($data['doctor_id']);
                $timeInterval = TimeInterval::find($data['time_interval_id']);
                $appointments = Appointment::where('time_interval_id', $data['time_interval_id'])
                                            ->where('date_appointment', $data['date_appointment'])
                                            ->where('doctor_id', $data['doctor_id'])
                                            ->get();

                $startWorkDay = strtotime($doctor->start_work_day);
                $endWorkDay = strtotime($doctor->end_work_day);
                $timeAppointment = strtotime(date('H:i', strtotime($timeInterval->start_time)));

                $interval = self::TIME_INTERVAL;

                // выходит за рамки рабочего дня?
                if ($timeAppointment < $startWorkDay || $timeAppointment > strtotime("-{$interval} minutes", $endWorkDay)) {
                    $fail('The '.$attribute.' is invalid.');
                }

                // имеются пересечения по времени?
                if (count($appointments)) {
                    $fail('The '.$attribute.' is invalid.');
                }
            }
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $appointment = Appointment::firstOrCreate([
            'date_appointment' => $data['date_appointment'],
            'time_interval_id' => $data['time_interval_id'],
            'doctor_id' => $data['doctor_id'],
            'patient_id' => $data['patient_id'],
        ], $data);

        return new JsonResource($appointment);
    }

    public function index(Request $request)
    {
        $data = $request->all();

        $rules = [
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $query = Appointment::query();

        // фильтрация по полям: ФИО врача
        if (isset($data['doc_last_name'])) {
            $query->whereHas('doctor', function($query) use ($data) {
                $query->where('last_name', $data['doc_last_name']);
            });
        }

        if (isset($data['doc_first_name'])) {
            $query->whereHas('doctor', function($query) use ($data) {
                $query->where('first_name', $data['doc_first_name']);
            });
        }

        if (isset($data['doc_middle_name'])) {
            $query->whereHas('doctor', function($query) use ($data) {
                $query->where('middle_name', $data['doc_middle_name']);
            });
        }

        // фильтрация по полям: ФИО пациента
        if (isset($data['last_name'])) {
            $query->whereHas('patient', function($query) use ($data) {
                $query->where('last_name', $data['last_name']);
            });
        }

        if (isset($data['first_name'])) {
            $query->whereHas('patient', function($query) use ($data) {
                $query->where('first_name', $data['first_name']);
            });
        }

        if (isset($data['middle_name'])) {
            $query->whereHas('patient', function($query) use ($data) {
                $query->where('middle_name', $data['middle_name']);
            });
        }

        // фильтрация по интервалу дат записей
        if (isset($data['start_date'])) {
            $query->where('date_appointment', '>=', $data['start_date']);
        }

        if (isset($data['end_date'])) {
            $query->where('date_appointment', '<=', $data['end_date']);
        }

        $query->orderBy('date_appointment');
        $query->whereHas('timeInterval', function($query) {
            $query->orderBy('start_time');
        });

        $appointments = $query->paginate(self::PER_PAGE);

        return response()->json($appointments, 200);
    }
}

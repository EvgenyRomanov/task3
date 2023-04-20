<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'last_name' => 'required|max:128',
            'first_name' => 'required|max:128',
            'middle_name' => 'nullable|max:128',
            'phone' => 'required|max:64',
            'start_work_day' => 'required|date_format:H:i',
            'end_work_day' => 'required|date_format:H:i',
            'birthday' => 'nullable|date_format:Y-m-d',
            'email' => 'nullable|email',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $doctor = Doctor::firstOrCreate([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'phone' => $data['phone'],
            'start_work_day' => $data['start_work_day'],
            'end_work_day' => $data['end_work_day'],
        ], $data);

        return new JsonResource($doctor);
    }
}

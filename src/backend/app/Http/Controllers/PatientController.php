<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'last_name' => 'required_without_all:first_name,middle_name',
            'first_name' => 'required_without_all:last_name,middle_name',
            'middle_name' => 'required_without_all:last_name,first_name',
            'legNP' => 'required|size:11|regex:/^[0-9]+$/',
            'birthday' => 'nullable|date_format:Y-m-d',
            'place_of_residence' => 'nullable',
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $patient = Patient::firstOrCreate([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'legNP' => $data['legNP'],
        ], $data);

        return new JsonResource($patient);
    }
}

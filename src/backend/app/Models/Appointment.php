<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\TimeInterval;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $guarded = [];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function timeInterval()
    {
        return $this->belongsTo(TimeInterval::class);
    }
}

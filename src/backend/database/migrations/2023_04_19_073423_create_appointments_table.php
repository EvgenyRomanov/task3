<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->date('date_appointment');
            $table->unsignedBigInteger('time_interval_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('patient_id');
            $table->timestamps();

            $table->index('time_interval_id', 'appointment_time_interval_idx');
            $table->foreign('time_interval_id', 'appointment_time_interval_fk')->on('time_intervals')->references('id');

            $table->index('doctor_id', 'appointment_doctor_idx');
            $table->foreign('doctor_id', 'appointment_doctor_fk')->on('doctors')->references('id');

            $table->index('patient_id', 'appointment_patient_idx');
            $table->foreign('patient_id', 'appointment_patient_fk')->on('patients')->references('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeIntervalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_intervals', function (Blueprint $table) {
            $table->id();
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });

        DB::statement("INSERT INTO time_intervals (start_time, end_time)
                            VALUES ('06:00:00', '06:30:00'),
                                   ('06:30:00', '07:00:00'),
                                   ('07:00:00', '07:30:00'),
                                   ('07:30:00', '08:00:00'),
                                   ('08:00:00', '08:30:00'),
                                   ('08:30:00', '09:00:00'),
                                   ('09:00:00', '09:30:00'),
                                   ('09:30:00', '10:00:00'),
                                   ('10:00:00', '10:30:00'),
                                   ('10:30:00', '11:00:00'),
                                   ('11:00:00', '11:30:00'),
                                   ('11:30:00', '12:00:00'),
                                   ('12:00:00', '12:30:00'),
                                   ('12:30:00', '13:00:00'),
                                   ('13:00:00', '13:30:00'),
                                   ('13:30:00', '14:00:00'),
                                   ('14:00:00', '14:30:00'),
                                   ('14:30:00', '15:00:00'),
                                   ('15:00:00', '15:30:00'),
                                   ('15:30:00', '16:00:00'),
                                   ('16:00:00', '16:30:00')
        ");

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_intervals');
    }
}

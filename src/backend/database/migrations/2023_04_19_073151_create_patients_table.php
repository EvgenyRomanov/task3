<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('last_name', 128)->nullable();;
            $table->string('first_name', 128)->nullable();;
            $table->string('middle_name', 128)->nullable();;
            $table->char('legNP', 11); // СНИЛС
            $table->date('birthday')->nullable();
            $table->string('place_of_residence')->nullable(); // место жительства
            $table->timestamps();
        });

        DB::statement('ALTER TABLE patients ADD CONSTRAINT check_nullable CHECK ("last_name" is not null or "first_name" is not null or "middle_name" is not null)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}

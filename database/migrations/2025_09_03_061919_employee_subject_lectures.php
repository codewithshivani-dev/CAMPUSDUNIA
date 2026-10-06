<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeSubjectLectures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('employee_subject_lectures', function (Blueprint $table) {
            $table->id();

            $table->string('emp_assign_subject_id'); // FK to assign_subjects_to_employee.id
            $table->enum('frequency', ['one_time','daily','weekly','monthly']);

            // time of day
            $table->time('start_time');
            $table->time('end_time');

            // when this schedule is valid
            $table->date('valid_from');
            $table->date('valid_to')->nullable(); // null => open ended

            // recurrence specific
            $table->json('days_of_week')->nullable(); // weekly: [0..6] (Sun..Sat)
            $table->unsignedTinyInteger('day_of_month')->nullable(); // monthly: 1..31

            // (optional, for room/location)
            $table->string('location')->nullable();

            $table->timestamps();

            $table->foreign('emp_assign_subject_id')
                ->references('emp_assign_subject_id')->on('assign_subjects_to_employee')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::dropIfExists('employee_subject_lectures');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeAttendanceLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Logs Table
        Schema::create('employee_attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id');
            $table->enum('check_type', ['IN','OUT']);
            $table->dateTime('check_time');
            $table->string('ip_address', 45)->nullable();
            $table->string('device_info')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();

            $table->foreign('attendance_id')
                ->references('id')->on('employee_attendance')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('employee_attendance_logs');
    }
}

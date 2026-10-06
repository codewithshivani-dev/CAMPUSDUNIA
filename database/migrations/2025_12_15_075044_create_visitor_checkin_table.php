<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorCheckinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_checkin', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('visitor_code')->nullable();
            $table->string('gate_id')->nullable();
            $table->string('visitor_checkin_id')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('assign_by')->nullable();
            $table->timestamp('check_in_time')->nullable();
            $table->string('front_desk_id')->nullable();
            $table->string('assign_to')->nullable();
            $table->enum('check_in_status', ['allowed','no_allowed'])->default('allowed');
            $table->string('checkin_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_visitor_checkin');
    }
}

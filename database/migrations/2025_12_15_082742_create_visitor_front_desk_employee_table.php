<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorFrontDeskEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_front_desk_employee', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('front_desk_id')->unique();//"FD-42512"
            $table->string('employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('department_category_id')->nullable();
            $table->string('department_category_name')->nullable();
            $table->string('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->string('position');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('office_room')->nullable();
            $table->string('extension')->nullable();
            $table->enum('availability_status', ['available', 'busy', 'out_of_office', 'in_meeting'])->default('available');
            $table->boolean('can_host_visitors')->default(true);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('visitor_front_desk_employee');
    }
}

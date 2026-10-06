<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('employee_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('leave_type')->nullable(); 
            $table->integer('total_allocated')->default(0);
            $table->integer('used')->default(0);
            $table->integer('remaining')->default(0);
            $table->string('session_year')->nullable(); 
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
        Schema::dropIfExists('employee_leave_balances');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeApprovalChains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_approval_chains', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');   // FK -> employees table
            $table->unsignedBigInteger('approval_step_id'); // FK -> approval_steps
            $table->unsignedBigInteger('approver_id');      // The actual approver (manager, HR, etc.)
            $table->timestamps();

            $table->foreign('approval_step_id')->references('id')->on('approval_steps')->onDelete('cascade');
            $table->foreign('employee_id')->references('employee_id')->on('employee_details')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_approval_chains');
    }
}

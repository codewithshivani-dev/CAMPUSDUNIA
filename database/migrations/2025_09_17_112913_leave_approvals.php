<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LeaveApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('leave_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leave_id');   // FK -> employee_leaves.id
            $table->unsignedBigInteger('approval_step_id'); // FK -> approval_steps.id
            $table->unsignedBigInteger('approver_id');      // Who approved/rejected
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamp('approved_on')->nullable();
            $table->timestamps();

            $table->foreign('leave_id')->references('id')->on('employee_leaves')->onDelete('cascade');
            $table->foreign('approval_step_id')->references('id')->on('approval_steps')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::dropIfExists('leave_approvals');
    }
}

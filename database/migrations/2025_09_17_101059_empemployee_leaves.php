<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmpemployeeLeaves extends Migration
{
    /**
     * Run the migrations.
     * 
     *
     * @return void
     */
    public function up()
    {
       Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id'); // FK -> employees table
            $table->enum('leave_type', ['Sick', 'Casual', 'Earned', 'Unpaid', 'Maternity', 'Other']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason')->nullable();
            $table->enum('final_status', ['Pending', 'Approved', 'Rejected', 'Cancelled'])->default('Pending');
            $table->timestamps();
             // Foreign Keys
            $table->foreign('employee_id')
                ->references('employee_id')
                ->on('employee_details')
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
         Schema::dropIfExists('employee_leaves');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('student_hash_id');
            $table->string('leave_type');
            $table->enum('leave_duration_type', ['Full Day', 'Half Day', 'Short Leave']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('total_days');
            $table->text('reason')->nullable();
            $table->string('leave_document')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->unsignedBigInteger('approved_by')->nullable(); // Admin/teacher who approved
            $table->timestamps();

            $table->foreign('student_hash_id')->references('student_hash_id')->on('student_parent_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_leaves');
    }
}

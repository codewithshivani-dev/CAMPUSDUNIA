<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_attendance', function (Blueprint $table) {
            $table->id(); // Primary Key

            // Relations
            $table->string('student_attandance_id');
            $table->string('emp_assign_subject_id');
            $table->string('student_id');   // FK -> students.id

            // Attendance details
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['Present', 'Absent', 'Leave','Pending'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamp('marked_time')->useCurrent(); // auto set when inserted
            $table->string('academic_year', 9); // e.g., "2025-2026"

            $table->timestamps();

            // Foreign Keys
            $table->foreign('emp_assign_subject_id')
                ->references('emp_assign_subject_id')
                ->on('assign_subjects_to_employee')
                ->onDelete('cascade');
            $table->foreign('student_id')
                ->references('student_hash_id')
                ->on('student_parent_details')
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
        Schema::dropIfExists('student_attendance');
    }
}

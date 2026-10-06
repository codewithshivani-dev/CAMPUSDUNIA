<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AssignSubjectsToEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_subjects_to_employee', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->string('emp_assign_subject_id')->unique();
            $table->string('employee_id'); // FK -> employee_details.id
            $table->string('course_detail_id'); 
            $table->string('subject_id');  // FK -> subjects_coursewise.id
            $table->string('semester_id')->nullable(); // optional FK -> semesters.id

            // Extra fields
            $table->string('academic_year', 9); // e.g., "2025-2026"
            $table->date('assigned_date');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Foreign Keys (make sure referenced columns are PK/unique)
            $table->foreign('employee_id')
                  ->references('employee_id')
                  ->on('employee_details')
                  ->onDelete('cascade');

            $table->foreign('subject_id')
                  ->references('subject_id')
                  ->on('subjects_coursewise')
                  ->onDelete('cascade');

            $table->foreign('course_detail_id')
                ->references('product_id')
                ->on('product_details')
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
        Schema::dropIfExists('assign_subjects_to_employee');
    }
}

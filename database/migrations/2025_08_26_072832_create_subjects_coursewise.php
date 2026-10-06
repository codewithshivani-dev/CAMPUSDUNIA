<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsCoursewise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects_coursewise', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->string('course_detail_id'); // FK -> courses.id
            $table->string('subject_id')->unique(); // custom subject id
            $table->string('semester_id')->nullable(); // optional FK -> semesters.id

            // Extra fields
            $table->string('academic_year', 9); // e.g., "2025-2026"
            $table->date('assigned_date');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('remarks')->nullable();
            $table->timestamps();
             // Foreign Keys
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
        Schema::dropIfExists('subjects_coursewise');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_fee_structures', function (Blueprint $table) {
           $table->id();
            $table->string('product_id'); // Reference to product_details table
            $table->string('branch_id');
            $table->string('department_id');
            $table->string('course_type');
            $table->string('sub_type');
            
            // Batch/Academic Year Information
            $table->string('academic_year');
            $table->integer('batch_year');
            $table->date('batch_start_date');
            $table->date('batch_end_date');
            $table->string('session_range');
            
            // Course Duration Info
            $table->string('course_duration');
            $table->integer('course_length');
            $table->string('mode_of_course');
            $table->string('mode_type');
            
            // Fee Information
            $table->decimal('total_fee', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            
            // Fee categories as JSON
            $table->json('course_fee')->nullable();
            $table->json('hostel_fee')->nullable();
            $table->json('transportation_fee')->nullable();
            $table->json('registration_fee')->nullable();
            $table->json('miscellaneous_fee')->nullable();
            $table->json('custom_fees')->nullable();
            
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('product_id')->references('product_id')->on('product_details')->onDelete('cascade');
            
            // Unique constraint for batch
            $table->unique(['product_id', 'academic_year', 'batch_year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_fee_structures');
    }
}

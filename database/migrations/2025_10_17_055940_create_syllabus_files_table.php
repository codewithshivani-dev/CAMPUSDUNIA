<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabuses', function (Blueprint $table) {
        $table->id();
        $table->string('institute_id')->nullable();
        $table->string('syllabus_id')->unique(); // like SYL-XXXX
        $table->string('employee_id'); // from employee_details
        $table->enum('term_type', ['monthly', 'semester', 'yearly'])->nullable();
        $table->string('term_value')->nullable();
        $table->unsignedBigInteger('course_detail_id'); // from product_details.product_id
        $table->string('subject_id'); // from subjects_coursewise.subject_id
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('file_path');
        $table->string('file_name')->nullable();
        $table->date('uploaded_date');
        $table->timestamps();
        $table->foreign('institute_id')->references('fincap_merchant_id')->on('institutes')->onDelete('cascade');
        $table->foreign('employee_id')->references('employee_id')->on('employee_details')->onDelete('cascade');
    
    });

    }

    /**
     * 
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syllabuses');
    }
}































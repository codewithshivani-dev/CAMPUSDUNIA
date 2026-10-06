<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id');   // link to assignment
            $table->string('file_path');
            $table->string('file_type')->nullable();      // pdf, docx, jpg, etc.
            $table->timestamps();

            // Foreign key
            $table->foreign('assignment_id')
                ->references('id')->on('assignments')
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
        Schema::dropIfExists('employee_files');
    }
}

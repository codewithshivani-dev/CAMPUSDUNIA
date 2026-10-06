<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SyllabusFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('syllabus_files', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('syllabus_id');
        $table->string('file_path');
        $table->string('file_name')->nullable();
        $table->timestamps();

        $table->foreign('syllabus_id')->references('id')->on('syllabuses')->onDelete('cascade');
    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('syllabuses');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('syllabus_topics', function (Blueprint $table) {

            // Primary key
            $table->id();

            // String based IDs
            $table->string('topic_id')->unique();
            $table->string('institute_id');
            $table->string('syllabus_id');
            $table->string('branch_id');
            $table->string('subject_id');

            // Topic Information
            $table->string('topic_name');
            $table->string('book_name')->nullable();

            // Date fields
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Description
            $table->text('description')->nullable();

            // Created_at & Updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_topics');
    }
};

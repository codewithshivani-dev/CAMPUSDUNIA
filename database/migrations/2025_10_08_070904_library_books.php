<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LibraryBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->string('librarybook_id')->unique(); // ID No
            // Link to library_book_categories table
            $table->foreignId('book_categories_id')
                ->constrained('library_book_categories')
                ->onDelete('cascade');
            $table->string('title'); // Book Name
            $table->string('subject'); // Subject
            $table->string('writer_name')->nullable(); // Writer Name
            $table->string('class')->nullable(); // Class

            $table->date('publishing_date')->nullable(); // Publishing Date
            $table->date('uploaded_date')->nullable(); // Uploaded Date

            $table->integer('total_copies')->default(1); // Copies
            $table->integer('available_copies')->default(1); // Copies
            $table->enum('status', ['available', 'unavailable'])->default('available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('library_books');
    }
}

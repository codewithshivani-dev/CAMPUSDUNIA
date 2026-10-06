<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->string('title'); // Book Name
            $table->string('subject'); // Subject
            $table->string('writer_name')->nullable(); // Writer Name
            $table->string('class')->nullable(); // Class
            $table->string('id_no')->unique(); // ID No

            $table->date('publishing_date')->nullable(); // Publishing Date
            $table->date('uploaded_date')->nullable(); // Uploaded Date

            $table->integer('copies')->default(1); // Copies
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
        Schema::dropIfExists('books');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books_fines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_issue_id');
            $table->decimal('amount', 10, 2)->default(0);
            $table->integer('days_overdue')->default(0);
            $table->enum('paid_status', ['unpaid', 'paid', 'waive-off'])->default('unpaid');
            $table->timestamps();

            // Foreign key constraint (optional but recommended)
            $table->foreign('book_issue_id')->references('id')->on('book_issues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books_fines');
    }
}

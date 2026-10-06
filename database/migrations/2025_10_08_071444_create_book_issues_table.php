<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->string('book_issue_id')->unique(); // custom code like BI2025-0001

            // Link to library_books table
            $table->foreignId('library_book_id')
                ->constrained('library_books')
                ->onDelete('cascade');

            // Polymorphic relation (StudentParentDetails or EmployeeDetails)
            $table->unsignedBigInteger('issueable_id');
            $table->string('issueable_type'); // App\Models\StudentParentDetails or App\Models\EmployeeDetails

            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['issued', 'returned', 'overdue', 'reissued'])->default('issued');

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
        Schema::dropIfExists('book_issues');
    }
}

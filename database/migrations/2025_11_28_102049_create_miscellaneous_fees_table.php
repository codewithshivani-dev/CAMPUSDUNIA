<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiscellaneousFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_miscellaneous_fees', function (Blueprint $table) {
            $table->id();
            // Relations
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('student_hash_id')->nullable();   // Link to student
            $table->string('batch_id')->nullable(); // from academic table
            $table->string('academic_year_id')->nullable();
            $table->string('fee_duration_type')->nullable();
            // Fee Details
            $table->decimal('miscellaneous_fee', 12, 2)->default(0);
            $table->decimal('miscellaneous_total_fee', 12, 2)->default(0);
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->date('pay_date')->nullable();

            // Payment details
            $table->string('fee_type')->nullable();
            $table->enum('payment_status', ['pending','unpaid', 'paid', 'partial'])->default('pending');

            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('student_miscellaneous_fees');
    }
}

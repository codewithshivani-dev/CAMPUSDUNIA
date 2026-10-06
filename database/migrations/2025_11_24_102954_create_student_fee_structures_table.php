<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_course_fee_structures', function (Blueprint $table) {
            $table->id();
            // Institution details
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();

            // Student reference
            $table->string('student_hash_id')->nullable(); // unique student UID
            $table->string('fee_duration_type')->nullable(); // unique student UID

            // Academic mapping
            $table->string('product_id')->nullable();
            $table->string('batch_id')->nullable();
            $table->string('academic_year_id')->nullable();

            // Fee components
            $table->decimal('course_fee', 12, 2)->default(0);

            // Extra recommended fields
            $table->decimal('course_total_fee', 12, 2)->default(0);           // auto-calculated total
            $table->decimal('discount_amount', 12, 2)->default(0);     // scholarship / waiver
            $table->decimal('final_payable_fee', 12, 2)->default(0);   // total - discount
            $table->date('pay_date')->nullable();  
            $table->date('due_date')->nullable();                      // payment due date
            $table->text('remarks')->nullable();                       // admin notes

            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid'
            ])->default('pending');

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
        Schema::dropIfExists('student_course_fee_structures');
    }
}

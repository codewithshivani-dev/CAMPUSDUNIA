<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollpolicyOtherDeductions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrollpolicy_other_deductions', function (Blueprint $table) {
          $table->id();

            // One-to-one with payroll policy
            $table->unsignedBigInteger('payroll_policy_id')->unique();

            /* ------------------------------
             | Insurance
             ------------------------------ */
            $table->boolean('insurance_selected')->default(false);
            $table->enum('insurance_type', ['fixed', 'percentage'])->default('fixed');

            /* ------------------------------
             | Loan Deduction
             ------------------------------ */
            $table->boolean('loan_selected')->default(false);
            $table->enum('loan_type', ['fixed', 'percentage'])->default('fixed');

            /* ------------------------------
             | Advance Salary
             ------------------------------ */
            $table->boolean('advance_selected')->default(false);
            $table->enum('advance_type', ['fixed', 'percentage'])->default('fixed');

            /* ------------------------------
             | Custom Deductions
             ------------------------------ */
            $table->json('custom_deductions')->nullable();
            /*
                Example:
                [
                    { "name": "Mobile Bill", "selected": true, "type": "fixed" },
                    { "name": "Food Deduction", "selected": false, "type": "percentage" }
                ]
            */

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
        Schema::dropIfExists('payrollpolicy_other_deductions');
    }
}

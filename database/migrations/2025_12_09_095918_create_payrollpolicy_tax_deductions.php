<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollpolicyTaxDeductions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrollpolicy_tax_deductions', function (Blueprint $table) {
            $table->id();

            // One-to-one with payroll_policy
            $table->unsignedBigInteger('payroll_policy_id')->unique();

            /* -------------------------------
             | Professional Tax (PT)
             ------------------------------- */
            $table->boolean('pt_selected')->default(false);
            $table->enum('pt_type', ['percentage', 'fixed', 'slabs'])->default('fixed');

            // PT slabs stored as JSON if selected
            $table->json('pt_slabs')->nullable();
            /*
                Example:
                [
                   { "min": 0, "max": 7500, "amount": 0 },
                   { "min": 7501, "max": 10000, "amount": 175 }
                ]
            */

            /* -------------------------------
             | Labor State Tax (LST)
             ------------------------------- */
            $table->boolean('lst_selected')->default(false);
            $table->enum('lst_type', ['percentage', 'fixed', 'slabs'])->default('fixed');
            $table->json('lst_slabs')->nullable();

            /* -------------------------------
             | TDS (auto-calculated)
             ------------------------------- */
            $table->boolean('tds_selected')->default(false);
            $table->json('tds_slabs')->nullable();

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
        Schema::dropIfExists('payrollpolicy_tax_deductions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollpolicyAllowances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrollpolicy_allowances', function (Blueprint $table) {
             $table->id();

            // Same payroll_policy_id for this policy
           $table->string('payroll_policy_id')->unique();
           

            // -----------------------------
            // Default Allowances
            // -----------------------------

            // HRA
            $table->boolean('hra_selected')->default(false);
            $table->enum('hra_type', ['percentage', 'fixed'])->default('percentage');

            // Conveyance
            $table->boolean('conveyance_selected')->default(false);
            $table->enum('conveyance_type', ['percentage', 'fixed'])->default('fixed');

            // Medical
            $table->boolean('medical_selected')->default(false);
            $table->enum('medical_type', ['percentage', 'fixed'])->default('fixed');

            // Special
            $table->boolean('special_selected')->default(false);
            $table->enum('special_type', ['percentage', 'fixed'])->default('fixed');

            // LTA
            $table->boolean('lta_selected')->default(false);
            $table->enum('lta_type', ['percentage', 'fixed'])->default('fixed');

            // Education
            $table->boolean('education_selected')->default(false);
            $table->enum('education_type', ['percentage', 'fixed'])->default('fixed');

            // -----------------------------
            // Custom Allowances (name, selected, type)
            // Example:
            // [
            //   { "name": "Internet", "selected": true, "type": "fixed" },
            //   { "name": "Fuel", "selected": false, "type": "percentage" }
            // ]
            // -----------------------------
            $table->json('custom_allowances')->nullable();

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
        Schema::dropIfExists('payrollpolicy_allowances');
    }
}

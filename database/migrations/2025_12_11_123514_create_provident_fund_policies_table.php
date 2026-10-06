<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('provident_fund_policies', function (Blueprint $table) {

            $table->id();

            // String IDs (no foreign keys)
            $table->string('payroll_policy_id');
            $table->string('institute_id');

            // Financial Year
            $table->string('financial_year'); // Example: "2024-2025"

            /* -------------------------------
             | Provident Fund (PF) Settings
             -------------------------------*/

            $table->boolean('enable_pf')->default(false);

            // Employee PF
            $table->boolean('pf_employee_enabled')->default(false);
            $table->enum('pf_employee_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('pf_employee_value', 8, 2)->nullable();

            // Employer PF
            $table->boolean('pf_employer_enabled')->default(false);
            $table->enum('pf_employer_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('pf_employer_value', 8, 2)->nullable();

            /* -------------------------------
             | Employee State Insurance (ESI)
             -------------------------------*/

            $table->boolean('enable_esi')->default(false);

            // Employee ESI
            $table->boolean('esi_employee_enabled')->default(false);
            $table->enum('esi_employee_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('esi_employee_value', 8, 2)->nullable();

            // Employer ESI
            $table->boolean('esi_employer_enabled')->default(false);
            $table->enum('esi_employer_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('esi_employer_value', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('provident_fund_policies');
    }
};

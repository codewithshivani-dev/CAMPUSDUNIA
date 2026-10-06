<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_salary_structures', function (Blueprint $table) {

            // Auto-increment Primary Key
            $table->id();

            $table->string('salary_structure_id', 50)->unique();
            $table->string('payroll_policy_id', 50);
            $table->string('institute_id', 50);
            $table->string('branch_id', 50);
            $table->string('department_category_id', 50);
            $table->string('department_id', 50);
            $table->string('designation_id', 50);
            $table->string('employee_id', 50)->unique();

            // Annual CTC
            $table->decimal('fixed_ctc_annual', 12, 2)->unsigned()->default(0.00);
            $table->decimal('variable_ctc_annual', 12, 2)->unsigned()->default(0.00);
            $table->decimal('total_ctc_annual', 12, 2)->unsigned()->default(0.00);

            // Monthly Breakdown
            $table->decimal('monthly_fixed', 12, 2)->unsigned()->default(0.00);
            $table->decimal('monthly_variable', 12, 2)->unsigned()->default(0.00);

            // Basic Salary Configuration
            $table->decimal('basic_salary_percentage', 5, 2)->unsigned()->default(40.00);
            $table->decimal('basic_salary_amount', 12, 2)->unsigned()->default(0.00);

            // Status
            $table->boolean('is_active')->default(true);

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index(['institute_id', 'branch_id']);
            $table->index('payroll_policy_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_salary_structures');
    }
};

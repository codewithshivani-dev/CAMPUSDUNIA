<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryPreviewTable extends Migration
{
    public function up(): void
    {
        Schema::create('salary_previews', function (Blueprint $table) {
            $table->id();

            /* -------------------------------
             | Salary Structure Reference
             ------------------------------- */
            $table->string('salary_structure_id', 50)->index();

            /* -------------------------------
             | Earnings
             ------------------------------- */
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('total_earnings', 12, 2)->default(0);

            /* -------------------------------
             | Deductions
             ------------------------------- */
            $table->decimal('total_deductions', 12, 2)->default(0);

            /* -------------------------------
             | Salary Summary
             ------------------------------- */
            $table->decimal('gross_salary', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);

            /* -------------------------------
             | Additional Earnings
             ------------------------------- */
            $table->decimal('total_allowances', 12, 2)->default(0);
            $table->decimal('total_bonus', 12, 2)->default(0);
            $table->decimal('total_overtime', 12, 2)->default(0);

            /* -------------------------------
             | Employer Contributions
             ------------------------------- */
            $table->decimal('employer_pf', 12, 2)->default(0);
            $table->decimal('employer_esi', 12, 2)->default(0);

            /* -------------------------------
             | Cost to Company
             ------------------------------- */
            $table->decimal('total_monthly_cost', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_preview');
    }
}

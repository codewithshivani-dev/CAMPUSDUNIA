<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryStructureDeductionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('salary_structure_deductions', function (Blueprint $table) {
            $table->id();

            /* -------------------------------
             | Salary Structure (Unique)
             ------------------------------- */
            $table->string('salary_structure_id', 50)->unique();

            /* -------------------------------
             | Professional Tax (PT)
             ------------------------------- */
            $table->boolean('pt_selected')->default(false);
            $table->enum('pt_type', ['percentage', 'fixed', 'slabs'])->default('fixed');
            $table->decimal('pt_value', 10, 2)->nullable();
            $table->json('pt_slabs')->nullable();

            /* -------------------------------
             | Labor State Tax (LST)
             ------------------------------- */
            $table->boolean('lst_selected')->default(false);
            $table->enum('lst_type', ['percentage', 'fixed', 'slabs'])->default('fixed');
            $table->decimal('lst_value', 10, 2)->nullable();
            $table->json('lst_slabs')->nullable();

            /* -------------------------------
             | TDS (Auto Calculated)
             ------------------------------- */
            $table->boolean('tds_selected')->default(false);
            $table->decimal('tds_value', 10, 2)->nullable();
            $table->json('tds_slabs')->nullable();

            /* -------------------------------
             | Insurance
             ------------------------------- */
            $table->boolean('insurance_selected')->default(false);
            $table->enum('insurance_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('insurance_value', 10, 2)->nullable();

            /* -------------------------------
             | Advance Salary
             ------------------------------- */
            $table->boolean('advance_selected')->default(false);
            $table->enum('advance_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('advance_value', 10, 2)->nullable();

            /* -------------------------------
             | Custom Deductions
             ------------------------------- */
            $table->json('custom_deductions')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_structure_deductions');
    }
}

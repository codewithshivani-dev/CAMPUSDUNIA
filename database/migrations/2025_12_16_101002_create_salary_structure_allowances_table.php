<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryStructureAllowancesTable extends Migration
{
    public function up()
    {
        Schema::create('salary_structure_allowances', function (Blueprint $table) {

            $table->id();

            // Link to salary structure
            $table->string('salary_structure_id', 50);

            // -----------------------------
            // Default Allowances
            // -----------------------------

            // HRA
            $table->boolean('hra_selected')->default(false);
            $table->enum('hra_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('hra_value', 10, 2)->unsigned()->default(0.00);

            // Conveyance
            $table->boolean('conveyance_selected')->default(false);
            $table->enum('conveyance_type', ['percentage', 'fixed'])->default('fixed');
            $table->decimal('conveyance_value', 10, 2)->unsigned()->default(0.00);

            // Medical
            $table->boolean('medical_selected')->default(false);
            $table->enum('medical_type', ['percentage', 'fixed'])->default('fixed');
            $table->decimal('medical_value', 10, 2)->unsigned()->default(0.00);

            // Special Allowance
            $table->boolean('special_selected')->default(false);
            $table->enum('special_type', ['percentage', 'fixed'])->default('fixed');
            $table->decimal('special_value', 10, 2)->unsigned()->default(0.00);

            // LTA
            $table->boolean('lta_selected')->default(false);
            $table->enum('lta_type', ['percentage', 'fixed'])->default('fixed');
            $table->decimal('lta_value', 10, 2)->unsigned()->default(0.00);

            // Education
            $table->boolean('education_selected')->default(false);
            $table->enum('education_type', ['percentage', 'fixed'])->default('fixed');
            $table->decimal('education_value', 10, 2)->unsigned()->default(0.00);

            // -----------------------------
            // Custom Allowances (ONLY meta)
            // name, type, description
            // -----------------------------
            /*
            Example JSON:
            [
              {
                "name": "Internet Allowance",
                "type": "fixed",
                "description": "Monthly internet reimbursement"
              },
              {
                "name": "Fuel Allowance",
                "type": "percentage",
                "description": "Fuel expenses based on salary"
              }
            ]
            */
            $table->json('custom_allowances')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('salary_structure_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_structure_allowances');
    }
}

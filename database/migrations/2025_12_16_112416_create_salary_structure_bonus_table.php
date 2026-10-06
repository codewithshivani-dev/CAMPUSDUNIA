<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryStructureBonusTable extends Migration
{
    public function up()
    {
        Schema::create('salary_structure_bonuses', function (Blueprint $table) {

            $table->id();

            // Link to Salary Structure
            $table->string('salary_structure_id', 50);

            // Bonus Details
            $table->string('bonus_name', 100);

            $table->enum('bonus_type', [
                'fixed',
                'percentage',
                'ctc_percentage'
            ]);

            // Amount or Percentage
            $table->decimal('bonus_value', 12, 2)->unsigned()->default(0.00);

            // Optional description
            $table->string('description', 255)->nullable();

            $table->timestamps();

            // Index
            $table->index('salary_structure_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_structure_bonus');
    }
}

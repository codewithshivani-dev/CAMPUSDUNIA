<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryStructureOvertimesTable extends Migration
{
    public function up()
    {
        Schema::create('salary_structure_overtimes', function (Blueprint $table) {

            $table->id();

            $table->string('salary_structure_id', 50);

            $table->string('rule_name', 100);

            $table->enum('rate_type', [
                'per_hour',
                'fixed',
                'percentage'
            ]);

            $table->decimal('rate_value', 10, 2)->unsigned()->default(0.00);

            // Monday → Sunday (boolean map)
            /*
              Example:
              {
                "monday": true,
                "tuesday": true,
                "wednesday": true,
                "thursday": true,
                "friday": true,
                "saturday": false,
                "sunday": false
              }
            */
            $table->json('applicable_days');

            $table->decimal('min_hours', 5, 2)->unsigned()->nullable();

            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('salary_structure_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_structure_overtimes');
    }
}

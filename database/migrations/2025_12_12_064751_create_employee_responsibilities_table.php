<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeResponsibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_responsibilities', function (Blueprint $table) {
            $table->id();
            
            // First, check the exact data type of employee_id in employee_details table
            // Let's make sure it matches exactly
            $table->string('employee_id');
            
            $table->unsignedBigInteger('menu_item_id');
            $table->boolean('can_view')->default(true);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
            
            // Foreign key for menu_items
            $table->foreign('menu_item_id')
                  ->references('id')
                  ->on('menu_items')
                  ->onDelete('cascade');
            
            $table->unique(['employee_id', 'menu_item_id']);
            $table->index(['employee_id', 'menu_item_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_responsibilities');
        
    }
}
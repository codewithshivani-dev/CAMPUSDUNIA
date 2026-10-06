<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApprovalSteps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();
            $table->string('department'); 
            $table->string('role');     // e.g. 'Manager', 'HR', 'Admin'
            $table->integer('step');    // order: 1 = first, 2 = second, etc.
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('approval_steps');
    }
}

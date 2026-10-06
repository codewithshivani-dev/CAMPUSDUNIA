<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorCheckoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_checkout', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('visitor_code')->nullable();
            $table->string('gate_id')->nullable();
            $table->string('visitor_checkout_id')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('assign_by')->nullable();
            $table->timestamp('check_out_time')->nullable();
            $table->string('gatepass_id')->nullable();
            $table->enum('check_out_status', ['verify','not_verified','pending'])->default('verify');
            $table->string('checkout_remarks')->nullable();
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
        Schema::dropIfExists('visitor_checkout');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontdeskVisitorsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontdesk_visitors_log', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('visitor_attendent_log_id')->unique();//VDL-42512
            $table->string('front_desk_id')->nullable();
            $table->string('visitor_code')->nullable();
            $table->string('meeting_purpose')->nullable();
            $table->enum('meeting_attendent_type', ['self', 'transfer'])->default('self');
            $table->string('self_attendent_remarks')->nullable();
            $table->enum('visitors_log_status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('in_progress');
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
        Schema::dropIfExists('frontdesk_visitors_log');
    }
}

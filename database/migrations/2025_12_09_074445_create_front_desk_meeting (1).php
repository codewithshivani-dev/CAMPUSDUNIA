<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontDeskMeeting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('visitormeeting_created_by_frontdesk', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('front_desk_id')->nullable();// Get Visitor logs
            $table->string('visitor_attendent_log_id')->nullable();// Get Visitor logs
            $table->string('meeting_id')->unique(); // MTG-001, MTG-002
            $table->string('meeting_purpose')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('visitor_code')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('department_category_id')->nullable();
            $table->string('department_category_name')->nullable();
            $table->string('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->timestamp('scheduled_at');
            $table->integer('duration_minutes'); // 30, 60, 90, 120
            $table->string('location'); // Conference Room A, Meeting Room 101
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('meeting_notes')->nullable();
            $table->json('attachments')->nullable();
            $table->text('outcome_notes')->nullable();
            $table->enum('outcome_status', ['successful', 'needs_followup', 'rescheduled', 'unsuccessful'])->nullable();
            $table->timestamp('follow_up_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitormeeting_created_by_frontdesk');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneratePassForOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generate_pass_for_out', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            // Unique out-pass reference
            $table->string('out_pass_id')->unique(); // OP-42512

            // Relation fields
            $table->string('visitor_attendent_log_id')->nullable(); // VDL-42512
            $table->string('front_desk_id')->nullable(); // FD-42512
            $table->string('visitor_code')->nullable(); // VC-42512

            // Pass details
            $table->date('valid_date')->nullable();
            $table->time('valid_from')->nullable();
            $table->time('valid_to')->nullable();

            // Status
            $table->enum('pass_status', ['active', 'used', 'expired', 'cancelled'])->default('active');

            // Exit tracking
            $table->timestamp('exit_time')->nullable();
            $table->string('exit_verified_by')->nullable(); // staff/security

            // Remarks
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('generate_pass_for_out');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorPurposeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_purpose', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            // Purpose code (for internal reference)
            $table->string('purpose_code')->unique(); // VP-001

            // Display name
            $table->string('purpose_name'); // Admission, Fee Payment, Meeting, Delivery

            // Optional description
            $table->text('description')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Sorting order (for UI)
            $table->integer('sort_order')->default(0);

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
        Schema::dropIfExists('visitor_purpose');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterationForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('visitor_code')->unique(); // VR-A1B2C3
            $table->string('name');
            $table->string('contact_number');
            $table->string('email');
            $table->string('purpose'); // Meeting, Delivery, Interview, etc.
            $table->string('visitor_photo')->nullable(); // path to uploaded photo
            $table->boolean('otp_verified')->default(false);
            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('vehicle_type')->nullable(); // Car, Motorcycle, etc.
            $table->string('vehicle_number')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->json('vehicle_photos')->nullable(); // array of photo paths
            $table->string('registration_type'); // walkin, self
            $table->string('status')->default('registered'); // registered, checked-in, checked-out
            $table->timestamp('registration_time')->nullable();
            $table->text('additional_notes')->nullable();
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
        Schema::dropIfExists('visitors');
    }
}

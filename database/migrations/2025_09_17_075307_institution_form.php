<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstitutionForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->string('fincap_merchant_id')->nullable();
            $table->string('fincap_partner_id')->nullable();
            $table->string('name');
            $table->enum('type', ['College', 'University', 'School', 'Coaching', 'Tutor']);
            $table->enum('registration_type', ['Society', 'Trust', 'Pvt.Ltd', 'Ltd', 'Llp']);
            $table->string('email')->unique();
            $table->string('contact_number', 15);
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('state');
            $table->string('city');
            $table->string('pincode', 10);
            $table->date('establishment_date');
            $table->string('website')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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
        Schema::dropIfExists('institutes');
    }
}

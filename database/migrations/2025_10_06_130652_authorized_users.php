<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AuthorizedUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('authorized_users', function (Blueprint $table) {
        $table->id();
        $table->string('institute_id');
        $table->string('name');
        $table->string('email')->nullable();
        $table->string('phone_number')->nullable();
        $table->string('designation');
        $table->string('pan_number')->nullable();
        $table->string('aadhaar_number')->nullable();
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
        Schema::dropIfExists('authorized_users'); 
    }
}

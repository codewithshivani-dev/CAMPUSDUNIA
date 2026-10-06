<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StakeholderAuthorizedForm extends Migration
{
    /** 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('stakeholders_authorized', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone_number', 15);
            $table->enum('designation', ['owner', 'director', 'partner']);
            $table->string('pan_number', 10);
            $table->string('aadhaar_number', 12);
            $table->boolean('is_authorized_user')->default(false);
            $table->timestamps( );
            $table->string('authorized_name');
            $table->string('authorized_email');
            $table->string('authorized_phone_number', 15);
            $table->enum('authorized_designation', ['owner', 'director', 'admin', 'authorized person']);
            $table->string('authorized_pan_number', 10);
            $table->string('authorized_aadhaar_number', 12);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stakeholders_authorized');
    }
}

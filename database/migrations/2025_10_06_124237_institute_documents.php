<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstituteDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institute_documents', function (Blueprint $table) {
    $table->id();
    $table->string('institute_id');
    $table->string('registration_number')->nullable();
    $table->string('registration_document_path')->nullable();
    $table->string('pan_number')->nullable();
    $table->string('pan_document_path')->nullable();
    $table->json('pan_response')->nullable();
    $table->enum('pan_status', ['verified', 'unverified'])->default('unverified');
    $table->string('gst_number')->nullable();
    $table->string('gst_document_path')->nullable();
     $table->json('gst_response')->nullable();
    $table->enum('gst_status', ['verified', 'unverified'])->default('unverified');
    $table->string('logo_path')->nullable();
    $table->string('institute_image_path')->nullable();
    $table->timestamps();
    
    $table->foreign('institute_id')->references('fincap_merchant_id')->on('institutes');
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
           Schema::dropIfExists('institute_documents');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DocumentForm extends Migration
{
    /**
     * Run the migrations.
     * 
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institute_documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('institute_id')->constrained()->onDelete('cascade');
        $table->string('registration_number');
        $table->string('registration_document_path');
        $table->string('pan_number', 10);
        $table->string('pan_document_path');
        $table->json('pan_response')->nullable();
        $table->enum('pan_status', ['unverified', 'verified'])->default('unverified');
        $table->string('gst_number')->nullable();
        $table->string('gst_document_path')->nullable();
        $table->json('gst_response')->nullable();
        $table->enum('gst_status', ['unverified', 'verified'])->default('unverified');
        $table->string('logo_path');
        $table->string('institute_image_path');

        // 👇 reference stakeholders_authorized
        $table->foreignId('stakeholder_id')->constrained('stakeholders_authorized')->onDelete('cascade');

        $table->string('stakeholder_id_aadhaar_front_path');
        $table->string('stakeholder_id_aadhaar_back_path');
        $table->json('stakeholder_aadhaar_response')->nullable();
        $table->enum('stakeholder_aadhaar_status', ['unverified', 'verified'])->default('unverified');
        $table->string('stakeholder_id_pan_document_path');
        $table->json('stakeholder_pan_response')->nullable();
        $table->enum('stakeholder_pan_status', ['unverified', 'verified'])->default('unverified');

        // 👇 reference stakeholders_authorized too
        $table->foreignId('authorized_user_id')->constrained('stakeholders_authorized')->onDelete('cascade');

        $table->string('authorized_user_id_aadhaar_front_path');
        $table->string('authorized_user_id_aadhaar_back_path');
        $table->json('authorized_aadhaar_response')->nullable();
        $table->enum('authorized_aadhaar_status', ['unverified', 'verified'])->default('unverified');
        $table->string('authorized_user_id_pan_document_path');
        $table->json('authorized_pan_response')->nullable();
        $table->enum('authorized_pan_status', ['unverified', 'verified'])->default('unverified');

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
         Schema::dropIfExists('institute_documents');
    }
}

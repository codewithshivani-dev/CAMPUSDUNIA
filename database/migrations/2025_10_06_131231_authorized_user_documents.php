<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AuthorizedUserDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('authorized_user_documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('authorized_user_id')->constrained()->onDelete('cascade');
        $table->string('aadhaar_front_path')->nullable();
        $table->string('aadhaar_back_path')->nullable();
        $table->json('aadhaar_response')->nullable();
        $table->enum('aadhaar_status', ['verified', 'unverified'])->default('unverified');
        $table->string('pan_document_path')->nullable();
        $table->json('pan_response')->nullable();
        $table->enum('pan_status', ['verified', 'unverified'])->default('unverified');
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
        Schema::dropIfExists('stakeholder_documents'); 
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BeneficiaryDetailsForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('institute_beneficiary_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained()->onDelete('cascade');
            $table->string('beneficiary_name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('ifsc_code', 11);
            $table->enum('account_type', ['Current', 'Saving']);
            $table->string('cancelled_cheque_path');
            $table->boolean('terms_agreed')->default(false);
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
        Schema::dropIfExists('beneficiary_details');
    }
}

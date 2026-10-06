<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstituteBeneficiaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institute_beneficiary_details', function (Blueprint $table) {
        // Drop foreign key first if it exists
            $table->dropForeign(['institute_id']);
            $table->dropColumn('institute_id');
        });
         Schema::table('institute_beneficiary_details', function (Blueprint $table) {
        $table->string('institute_id')->after('id');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institute_beneficiary_details', function (Blueprint $table) {
            //
        });Schema::table('institute_beneficiary_details', function (Blueprint $table) {
        $table->dropColumn('institute_id');
        $table->foreignId('institute_id')->constrained()->onDelete('cascade');
    });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFincapIdsToInstitutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->string('fincap_merchant_id')->nullable()->after('gst_number');
            $table->string('fincap_partner_id')->nullable()->after('fincap_merchant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn(['fincap_merchant_id', 'fincap_partner_id']);
        });
    }
}

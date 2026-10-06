<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGraceAndOffdaysToShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->integer('grace_minutes')->default(15)->after('break_minutes');
            $table->string('weekly_off_days')->nullable()->after('grace_minutes'); // e.g. Sunday,Saturday
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn(['grace_minutes', 'weekly_off_days']);
        });
    }
}

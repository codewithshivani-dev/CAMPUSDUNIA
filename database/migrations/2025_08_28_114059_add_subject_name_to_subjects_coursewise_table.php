<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectNameToSubjectsCoursewiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects_coursewise', function (Blueprint $table) {
            $table->string('subject_name')->nullable()->after('subject_id'); // add column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects_coursewise', function (Blueprint $table) {
             $table->dropColumn('subject_name'); 
        });
    }
}

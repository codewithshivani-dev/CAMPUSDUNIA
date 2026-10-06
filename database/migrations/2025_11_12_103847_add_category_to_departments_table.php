<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryToDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('departments', function (Blueprint $table) {
            $table->string('department_category_id')->nullable()->after('department_id');
            $table->foreign('department_category_id')
                  ->references('department_category_id')
                  ->on('department_categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['department_category_id']);
            $table->dropColumn('department_category_id');
        });
    }
}

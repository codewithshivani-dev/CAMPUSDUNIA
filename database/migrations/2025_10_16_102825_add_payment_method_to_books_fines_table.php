<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToBooksFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books_fines', function (Blueprint $table) {
            // Add payment_method column after days_overdue
            $table->enum('payment_method', ['netbanking', 'upi', 'cc', 'dc', 'cash'])
                  ->nullable()
                  ->after('days_overdue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books_fines', function (Blueprint $table) {
            // Drop column on rollback
            $table->dropColumn('payment_method');
        });
    }
}

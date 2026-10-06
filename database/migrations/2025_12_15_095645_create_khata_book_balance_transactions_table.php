<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhataBookBalanceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khata_book_balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('khata_book_balance_id')->nullable();
            $table->enum('balance_transaction_type', ['customer', 'owned']);
            $table->string('customer_id')->nullable();
            $table->decimal('amount', 16, 2);
            $table->date('date');
            $table->string('description');
            $table->enum('type', ['spent', 'received', 'lend']);
            $table->string('category');
            $table->string('source')->nullable();
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
        Schema::dropIfExists('khata_book_balance_transactions');
    }
}

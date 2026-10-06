<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_links', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            // Internal Reference IDs
            $table->string('transaction_reference')->unique(); // Our internal txn ID
            $table->string('transaction_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('user_transaction_refered_id')->nullable(); // related to library issue

            $table->string('payment_link_id')->nullable()->comment('Razorpay generated link ID');
            $table->string('payment_link')->nullable()->comment('Razorpay short payment link URL');
            $table->string('gateway_payment_id')->nullable()->comment('Razorpay payment ID after payment');
            $table->decimal('amount', 10, 2)->comment('Payment amount in INR');
            $table->string('currency', 10)->default('INR');
            $table->string('payment_type')->nullable(); // fine, fee, deposit, etc.
            $table->enum('status', ['created', 'paid', 'failed', 'expired'])->default('created');
            $table->timestamps();

            // Optional foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('book_issue_id')->references('id')->on('book_issues')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_links');
    }
}

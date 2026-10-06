<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewayTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            // Internal Reference IDs
            $table->string('transaction_reference')->unique(); // Our internal txn ID
            $table->string('transaction_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('user_transaction_refered_id')->nullable(); // related to library issue
            
            // Payment Details
            $table->string('payment_gateway')->default('razorpay'); // razorpay, stripe, etc.
            $table->string('gateway_order_id')->nullable();
            $table->string('gateway_payment_id')->nullable();
            $table->string('gateway_signature')->nullable();
            
            // Amount Details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('INR');
            
            // Status Tracking
            $table->enum('status', ['created', 'pending', 'success', 'failed', 'refunded'])->default('created');
            $table->string('payment_type')->nullable(); // fine, fee, deposit, etc.
            $table->string('remarks')->nullable();
            
            // Gateway Response
            $table->json('gateway_response')->nullable();
            
            $table->timestamps();

            // Relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateway_transactions');
    }
}

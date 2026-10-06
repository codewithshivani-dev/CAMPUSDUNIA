<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PgGatewayTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pg_gateway_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_hash_id')->nullable();
            $table->string('institute_id')->nullable();
            $table->string('finacp_merchant_sub_category_id')->nullable();
            $table->string('product_id')->nullable();
            $table->text('fincap_partner_id')->nullable();
            $table->string('transaction_id')->nullable();

              // Gateway details
            $table->string('gateway_name')->nullable(); // Razorpay, Easebuzz, Stripe, etc.
            $table->string('gateway_order_id')->nullable(); // Order ID from payment gateway
            $table->string('gateway_payment_id')->nullable(); // Payment ID from payment gateway
            $table->string('gateway_signature')->nullable(); // Signature/hash for verification

            
            
            // Transaction type: 0 = Wallet, 1 = Card
            $table->enum('transaction_type', ['0', '1'])->default('0');
            
            $table->string('amount')->nullable();
            $table->string('balance')->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->nullable();
            $table->string('description')->nullable();
            $table->string('indicator')->nullable();
            $table->string('date')->nullable();
            $table->longText('transaction_json')->nullable();
            
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
         Schema::dropIfExists('pg_gateway_transactions');
    }
}

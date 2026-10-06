<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhataBookBalanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khata_book_balance', function (Blueprint $table) {
            $table->id();
            $table->string('institute_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('user_id')->nullable();
            $table->decimal('balance', 16, 2)->default(0.00);
            $table->string('last_updated')->nullable();
            $table->boolean('initial_deposit')->default(false);
            $table->enum('status', ['active', 'inactive', 'blocked']);
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
        Schema::dropIfExists('khata_book_balance');
    }
}

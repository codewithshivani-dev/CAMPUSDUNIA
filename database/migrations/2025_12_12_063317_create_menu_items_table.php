<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id(); // This creates an unsigned bigint column
            $table->string('name');
            $table->string('route')->nullable();
            $table->string('icon')->default('fas fa-circle');
            $table->unsignedBigInteger('parent_id')->nullable(); // Changed to unsignedBigInteger
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('permission_name')->nullable();
            $table->text('description')->nullable();
            $table->json('allowed_roles')->nullable();
            $table->timestamps();
            
            // Foreign key constraint - both columns are unsigned bigint
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('menu_items')
                  ->onDelete('cascade');
                  
            $table->index(['parent_id', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}

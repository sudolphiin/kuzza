<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('parent_recommended_items')) {
            Schema::create('parent_recommended_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('recommended_item_id');
                $table->unsignedBigInteger('parent_id');
                $table->unsignedBigInteger('student_id');
                $table->enum('status', ['pending', 'already_bought', 'selected_for_order', 'ordered', 'paid', 'delivered'])->default('pending');
                $table->decimal('payment_amount', 10, 2)->nullable();
                $table->dateTime('payment_date')->nullable();
                $table->dateTime('delivered_date')->nullable();
                $table->dateTime('deadline')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('recommended_item_id')->references('id')->on('school_recommended_items')->onDelete('cascade');
                $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_recommended_items');
    }
};

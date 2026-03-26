<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                // users.id is defined as increments() (INT UNSIGNED),
                // so use unsignedInteger here to keep the types compatible.
                $table->unsignedInteger('parent_id');
                $table->unsignedInteger('student_id')->nullable();
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->string('status', 40)->default('pending');
                $table->unsignedBigInteger('external_order_id')->nullable();
                $table->string('external_order_code', 50)->nullable();
                $table->string('external_source', 50)->nullable()->comment('e.g. mybidhaa');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('student_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('recommended_item_id');
                $table->unsignedBigInteger('parent_recommended_item_id')->nullable();
                $table->integer('quantity')->default(1);
                $table->decimal('price', 12, 2)->default(0);
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('recommended_item_id')->references('id')->on('school_recommended_items')->onDelete('cascade');
                $table->foreign('parent_recommended_item_id')->references('id')->on('parent_recommended_items')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};


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
        if (!Schema::hasTable('school_recommended_items')) {
            Schema::create('school_recommended_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->nullable();
                $table->string('item_name');
                $table->string('item_type')->comment('books, stationery, uniform, sport_attire, shoes, etc');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->nullable();
                $table->string('product_link')->nullable()->comment('Link to MyBidhaa eCommerce API');
                $table->string('image_url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('school_recommended_items');
    }
};

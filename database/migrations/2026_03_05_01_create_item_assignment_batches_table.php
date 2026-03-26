<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('item_assignment_batches')) {
            Schema::create('item_assignment_batches', function (Blueprint $table) {
                $table->id();
                // sm_schools.id and users.id use increments() (INT UNSIGNED)
                $table->unsignedInteger('school_id');
                $table->unsignedInteger('created_by');
                $table->string('scope', 20)->comment('all, class, student');
                $table->unsignedInteger('class_id')->nullable();
                $table->unsignedInteger('section_id')->nullable();
                $table->dateTime('deadline')->nullable();
                $table->timestamps();

                $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('item_assignment_batches');
    }
};


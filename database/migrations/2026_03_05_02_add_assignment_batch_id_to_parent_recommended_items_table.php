<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('parent_recommended_items', function (Blueprint $table) {
            if (! Schema::hasColumn('parent_recommended_items', 'assignment_batch_id')) {
                $table->unsignedBigInteger('assignment_batch_id')->nullable()->after('student_id');
                $table->foreign('assignment_batch_id')
                    ->references('id')
                    ->on('item_assignment_batches')
                    ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('parent_recommended_items', function (Blueprint $table) {
            if (Schema::hasColumn('parent_recommended_items', 'assignment_batch_id')) {
                $table->dropForeign(['assignment_batch_id']);
                $table->dropColumn('assignment_batch_id');
            }
        });
    }
};


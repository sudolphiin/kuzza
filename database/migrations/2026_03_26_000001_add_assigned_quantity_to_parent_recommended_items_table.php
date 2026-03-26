<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('parent_recommended_items') && !Schema::hasColumn('parent_recommended_items', 'assigned_quantity')) {
            Schema::table('parent_recommended_items', function (Blueprint $table) {
                $table->unsignedInteger('assigned_quantity')->default(1)->after('assignment_batch_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('parent_recommended_items') && Schema::hasColumn('parent_recommended_items', 'assigned_quantity')) {
            Schema::table('parent_recommended_items', function (Blueprint $table) {
                $table->dropColumn('assigned_quantity');
            });
        }
    }
};

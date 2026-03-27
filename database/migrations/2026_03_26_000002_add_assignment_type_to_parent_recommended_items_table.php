<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('parent_recommended_items') && !Schema::hasColumn('parent_recommended_items', 'assignment_type')) {
            Schema::table('parent_recommended_items', function (Blueprint $table) {
                $table->string('assignment_type', 20)->default('recommended')->after('assigned_quantity');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('parent_recommended_items') && Schema::hasColumn('parent_recommended_items', 'assignment_type')) {
            Schema::table('parent_recommended_items', function (Blueprint $table) {
                $table->dropColumn('assignment_type');
            });
        }
    }
};

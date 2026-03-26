<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('items') && ! Schema::hasColumn('items', 'price')) {
            Schema::table('items', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->nullable()->after('category');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('items') && Schema::hasColumn('items', 'price')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('pagebuilder.db_prefix', 'infixedu__') . 'pages';

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('slug')->nullable();
                $table->longText('settings')->nullable();
                $table->boolean('home_page')->default(false)->nullable();
                $table->boolean('is_default')->default(false)->nullable();
                $table->enum('status', ['draft', 'published'])->default('draft')->index();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->integer('published_by')->nullable();
                $table->unsignedInteger('school_id');
                $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        $tableName = config('pagebuilder.db_prefix', 'infixedu__') . 'pages';
        Schema::dropIfExists($tableName);
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'mpesa_checkout_request_id')) {
                $table->string('mpesa_checkout_request_id', 64)->nullable()->after('notes');
            }
            if (! Schema::hasColumn('orders', 'mpesa_merchant_request_id')) {
                $table->string('mpesa_merchant_request_id', 64)->nullable()->after('mpesa_checkout_request_id');
            }
            if (! Schema::hasColumn('orders', 'mpesa_result_code')) {
                $table->string('mpesa_result_code', 8)->nullable()->after('mpesa_merchant_request_id');
            }
            if (! Schema::hasColumn('orders', 'mpesa_result_desc')) {
                $table->string('mpesa_result_desc', 255)->nullable()->after('mpesa_result_code');
            }
            if (! Schema::hasColumn('orders', 'mpesa_receipt_number')) {
                $table->string('mpesa_receipt_number', 32)->nullable()->after('mpesa_result_desc');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'mpesa_checkout_request_id')) {
                $table->index('mpesa_checkout_request_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $cols = [
                'mpesa_checkout_request_id',
                'mpesa_merchant_request_id',
                'mpesa_result_code',
                'mpesa_result_desc',
                'mpesa_receipt_number',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

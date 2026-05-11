<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ussd_payment_requests')) {
            return;
        }

        Schema::create('ussd_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 128)->index();
            $table->string('phone_number', 48)->index();
            $table->string('flow_type', 32);
            $table->string('student_name', 191);
            $table->string('admission_reference', 64);
            $table->decimal('amount', 12, 2);
            $table->string('status', 32)->default('pending_mpesa');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ussd_payment_requests');
    }
};

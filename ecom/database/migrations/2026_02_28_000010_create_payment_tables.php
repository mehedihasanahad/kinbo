<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('restrict');
            $table->string('gateway', 20);       // sslcommerz|bkash|nagad|cod
            $table->string('tran_id', 100)->nullable();
            $table->string('val_id', 100)->nullable();
            $table->string('bank_tran_id', 100)->nullable();
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('BDT');
            $table->string('card_type', 50)->nullable();
            $table->string('status', 20)->default('pending'); // pending|success|failed|cancelled|refunded
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->index('order_id', 'idx_pay_trans_order_id');
            $table->index('tran_id', 'idx_pay_trans_tran_id');
            $table->index('val_id', 'idx_pay_trans_val_id');
            $table->index(['gateway', 'status'], 'idx_pay_trans_gateway_status');
        });

        Schema::create('manual_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->onDelete('cascade');
            $table->string('method', 10);            // bkash|nagad
            $table->string('sender_number', 20);
            $table->string('transaction_id', 100);
            $table->decimal('amount', 10, 2);
            $table->string('screenshot_path')->nullable();
            $table->string('status', 20)->default('pending'); // pending|verified|rejected
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index('transaction_id', 'idx_manual_pay_tran_id');
            $table->index(['status', 'created_at'], 'idx_manual_pay_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_payments');
        Schema::dropIfExists('payment_transactions');
    }
};

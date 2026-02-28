<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('shipping_rate_id')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('shipping_amount', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->string('status', 20)->default('pending');        // pending|processing|shipped|delivered|cancelled|returned
            $table->string('payment_status', 20)->default('unpaid'); // unpaid|pending_verification|paid|refunded|failed
            $table->string('payment_method', 20);                    // cod|bkash|nagad|sslcommerz
            // Shipping snapshot
            $table->string('ship_name', 191);
            $table->string('ship_phone', 20);
            $table->string('ship_address');
            $table->string('ship_city', 100);
            $table->string('ship_district', 100);
            $table->string('ship_zip', 10)->nullable();
            $table->text('notes')->nullable();
            $table->string('tracking_number', 100)->nullable();
            $table->timestamps();

            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
            $table->foreign('shipping_rate_id')->references('id')->on('shipping_rates')->onDelete('set null');

            $table->index('user_id', 'idx_orders_user_id');
            $table->index(['user_id', 'status'], 'idx_orders_user_status');
            $table->index(['status', 'created_at'], 'idx_orders_status_created');
            $table->index('payment_status', 'idx_orders_payment_status');
            $table->index('payment_method', 'idx_orders_payment_method');
            $table->index('coupon_id', 'idx_orders_coupon_id');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('product_name', 191);      // snapshot
            $table->string('variant_label', 191)->nullable(); // snapshot
            $table->decimal('unit_price', 10, 2);     // snapshot
            $table->smallInteger('quantity')->unsigned();
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
            $table->index('order_id', 'idx_order_items_order_id');
            $table->index('product_id', 'idx_order_items_product_id');
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('status', 20);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
            $table->index('order_id', 'idx_order_status_hist_order');
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('order_id')->constrained()->onDelete('restrict');
            $table->decimal('discount_amount', 10, 2);
            $table->timestamp('created_at')->nullable();

            $table->unique(['coupon_id', 'user_id', 'order_id'], 'udx_coupon_usage_unique');
            $table->index('user_id', 'idx_coupon_usage_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

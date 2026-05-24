<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courier_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('courier', 30);                      // steadfast | pathao | …
            $table->string('consignment_id')->nullable();
            $table->string('invoice', 50)->index();
            $table->string('tracking_code', 50)->nullable();
            $table->string('status', 50)->default('pending');   // mirrors provider statuses
            $table->decimal('cod_amount', 10, 2)->default(0);
            $table->json('response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamps();

            $table->unique('order_id');                         // one active courier order per order
            $table->index(['courier', 'status']);
            $table->index('consignment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courier_orders');
    }
};

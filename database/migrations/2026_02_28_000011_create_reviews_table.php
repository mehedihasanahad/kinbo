<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->tinyInteger('rating')->unsigned();
            $table->string('title', 191)->nullable();
            $table->text('body')->nullable();
            $table->tinyInteger('is_approved')->default(0);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->index(['product_id', 'is_approved'], 'idx_reviews_product_approved');
            $table->index(['product_id', 'rating'], 'idx_reviews_product_rating');
            $table->index('user_id', 'idx_reviews_user_id');
            $table->index('order_id', 'idx_reviews_order_id');
        });

        Schema::create('review_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('is_helpful');
            $table->timestamp('created_at')->nullable();

            $table->unique(['review_id', 'user_id'], 'udx_review_votes_user_review');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_votes');
        Schema::dropIfExists('reviews');
    }
};

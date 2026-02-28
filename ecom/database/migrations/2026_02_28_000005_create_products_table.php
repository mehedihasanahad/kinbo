<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('sku', 100)->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->smallInteger('low_stock_threshold')->unsigned()->default(5);
            $table->decimal('weight', 8, 3)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_featured')->default(0);
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->index(['category_id', 'is_active'], 'idx_products_category_active');
            $table->index(['brand_id', 'is_active'], 'idx_products_brand_active');
            $table->index(['is_featured', 'is_active'], 'idx_products_featured');
            $table->index('price', 'idx_products_price');
            $table->index('sale_price', 'idx_products_sale_price');
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->char('locale', 2);
            $table->string('name', 191);
            $table->string('slug', 191);
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('meta_title', 191)->nullable();
            $table->string('meta_description')->nullable();

            $table->unique(['product_id', 'locale'], 'udx_prod_trans_prod_locale');
            $table->index(['slug', 'locale'], 'idx_prod_trans_slug');
            $table->index(['name', 'locale'], 'idx_prod_trans_name');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->string('alt_text', 191)->nullable();
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->tinyInteger('is_primary')->default(0);
            $table->timestamps();

            $table->index('product_id', 'idx_prod_images_product_id');
            $table->index(['product_id', 'is_primary'], 'idx_prod_images_primary');
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku', 100)->nullable()->unique();
            $table->decimal('price_modifier', 10, 2)->default(0.00);
            $table->unsignedInteger('stock')->default(0);
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index('product_id', 'idx_prod_variants_product_id');
        });

        Schema::create('product_variant_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')
                ->references('id')->on('product_variants')->onDelete('cascade');
            $table->string('option_name', 50);
            $table->string('option_value', 100);

            $table->index('variant_id', 'idx_variant_options_variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_options');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
    }
};

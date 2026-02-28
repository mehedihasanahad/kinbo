<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('image')->nullable();
            $table->smallInteger('sort_order')->unsigned()->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            $table->index('parent_id', 'idx_categories_parent_id');
            $table->index(['is_active', 'sort_order'], 'idx_categories_active_sort');
        });

        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->char('locale', 2);
            $table->string('name', 191);
            $table->string('slug', 191);
            $table->string('meta_title', 191)->nullable();
            $table->string('meta_description')->nullable();

            $table->unique(['category_id', 'locale'], 'udx_cat_trans_cat_locale');
            $table->index(['slug', 'locale'], 'idx_cat_trans_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('categories');
    }
};

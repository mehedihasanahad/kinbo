<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index('is_active', 'idx_shipping_zones_active');
        });

        Schema::create('shipping_zone_districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')
                ->references('id')->on('shipping_zones')->onDelete('cascade');
            $table->string('district_name', 100);

            $table->unique(['zone_id', 'district_name'], 'udx_zone_district');
            $table->index('district_name', 'idx_zone_districts_district');
        });

        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')
                ->references('id')->on('shipping_zones')->onDelete('cascade');
            $table->string('method_name', 100);
            $table->decimal('cost', 10, 2);
            $table->decimal('free_shipping_above', 10, 2)->nullable();
            $table->tinyInteger('estimated_days_min')->unsigned()->nullable();
            $table->tinyInteger('estimated_days_max')->unsigned()->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index('zone_id', 'idx_shipping_rates_zone_id');
            $table->index(['zone_id', 'is_active'], 'idx_shipping_rates_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
        Schema::dropIfExists('shipping_zone_districts');
        Schema::dropIfExists('shipping_zones');
    }
};

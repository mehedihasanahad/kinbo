<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pathao_district_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('district_name', 100)->unique();
            $table->unsignedInteger('pathao_city_id');
            $table->unsignedInteger('pathao_zone_id');
            $table->unsignedInteger('pathao_area_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathao_district_mappings');
    }
};

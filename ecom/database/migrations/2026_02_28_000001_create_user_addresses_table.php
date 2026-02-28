<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label', 50)->nullable();
            $table->string('recipient_name', 191);
            $table->string('phone', 20);
            $table->string('address_line');
            $table->string('city', 100);
            $table->string('district', 100);
            $table->string('upazila', 100)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->tinyInteger('is_default')->default(0);
            $table->timestamps();

            $table->index('user_id', 'idx_user_addresses_user_id');
            $table->index(['user_id', 'is_default'], 'idx_user_addresses_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};

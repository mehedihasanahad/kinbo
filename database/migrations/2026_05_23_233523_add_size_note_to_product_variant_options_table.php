<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variant_options', function (Blueprint $table) {
            $table->string('size_note', 500)->nullable()->after('option_value');
        });
    }

    public function down(): void
    {
        Schema::table('product_variant_options', function (Blueprint $table) {
            $table->dropColumn('size_note');
        });
    }
};

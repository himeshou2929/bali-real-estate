<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties','monthly_rent')) {
                $table->unsignedBigInteger('monthly_rent')->nullable()->after('price_usd');
            }
            if (!Schema::hasColumn('properties','yearly_rent')) {
                $table->unsignedBigInteger('yearly_rent')->nullable()->after('monthly_rent');
            }
            if (!Schema::hasColumn('properties','leasehold_price')) {
                $table->unsignedBigInteger('leasehold_price')->nullable()->after('yearly_rent');
            }
            if (!Schema::hasColumn('properties','freehold_price')) {
                $table->unsignedBigInteger('freehold_price')->nullable()->after('leasehold_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            foreach (['freehold_price','leasehold_price','yearly_rent','monthly_rent'] as $col) {
                if (Schema::hasColumn('properties', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

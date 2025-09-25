<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('properties', function (Blueprint $t) {
            if (!Schema::hasColumn('properties','year_built'))    $t->integer('year_built')->nullable()->after('building_sqm');
            if (!Schema::hasColumn('properties','yield_percent')) $t->decimal('yield_percent',5,2)->nullable()->after('price_usd');
            if (!Schema::hasColumn('properties','views_count'))   $t->unsignedBigInteger('views_count')->default(0)->after('is_published');

            // インデックス（NULL許容でも検索に使用）
            $t->index('year_built');
            $t->index('yield_percent');
            $t->index('views_count');
        });
    }
    
    public function down(): void {
        Schema::table('properties', function (Blueprint $t) {
            if (Schema::hasColumn('properties','year_built'))    $t->dropIndex(['year_built']);
            if (Schema::hasColumn('properties','yield_percent')) $t->dropIndex(['yield_percent']);
            if (Schema::hasColumn('properties','views_count'))   $t->dropIndex(['views_count']);
            if (Schema::hasColumn('properties','year_built'))    $t->dropColumn('year_built');
            if (Schema::hasColumn('properties','yield_percent')) $t->dropColumn('yield_percent');
            if (Schema::hasColumn('properties','views_count'))   $t->dropColumn('views_count');
        });
    }
};

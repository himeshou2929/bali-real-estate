<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('properties', function (Blueprint $t) {
            if (!Schema::hasColumn('properties','address'))   $t->string('address')->nullable()->after('title');
            if (!Schema::hasColumn('properties','latitude'))  $t->decimal('latitude', 10, 7)->nullable()->after('address');
            if (!Schema::hasColumn('properties','longitude')) $t->decimal('longitude',10, 7)->nullable()->after('latitude');
        });
    }
    public function down(): void {
        Schema::table('properties', function (Blueprint $t) {
            foreach (['longitude','latitude','address'] as $c) if (Schema::hasColumn('properties',$c)) $t->dropColumn($c);
        });
    }
};
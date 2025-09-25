<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','phone'))  $table->string('phone')->nullable()->after('email');
            if (!Schema::hasColumn('users','avatar')) $table->string('avatar')->nullable()->after('phone');
            if (!Schema::hasColumn('users','language')) $table->string('language',5)->default('ja')->after('avatar');
            if (!Schema::hasColumn('users','currency')) $table->string('currency',3)->default('JPY')->after('language');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            foreach (['phone','avatar','language','currency'] as $col) {
                if (Schema::hasColumn('users',$col)) $table->dropColumn($col);
            }
        });
    }
};
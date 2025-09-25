<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','company'))     $table->string('company')->nullable()->after('name');
            if (!Schema::hasColumn('users','title'))       $table->string('title')->nullable()->after('company');
            if (!Schema::hasColumn('users','bio'))         $table->text('bio')->nullable()->after('title');
            if (!Schema::hasColumn('users','phone'))       $table->string('phone')->nullable()->after('email');
            if (!Schema::hasColumn('users','avatar'))      $table->string('avatar')->nullable()->after('phone');
            if (!Schema::hasColumn('users','website'))     $table->string('website')->nullable()->after('avatar');
            if (!Schema::hasColumn('users','whatsapp'))    $table->string('whatsapp')->nullable()->after('website');
            if (!Schema::hasColumn('users','line_id'))     $table->string('line_id')->nullable()->after('whatsapp');
            if (!Schema::hasColumn('users','location'))    $table->string('location')->nullable()->after('line_id');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            foreach (['company','title','bio','phone','avatar','website','whatsapp','line_id','location'] as $col) {
                if (Schema::hasColumn('users',$col)) $table->dropColumn($col);
            }
        });
    }
};
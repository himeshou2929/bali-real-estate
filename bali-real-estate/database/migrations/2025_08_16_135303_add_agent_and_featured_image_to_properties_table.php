<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('properties', function (Blueprint $table) {
            // agent_id, featured_image, and status already exist, add currency after price_usd
            if (!Schema::hasColumn('properties','currency')) {
                $table->string('currency')->default('USD')->after('price_usd');
            }
            // Add index for agent_id if it doesn't exist
            if (!Schema::hasIndex('properties', ['agent_id'])) {
                $table->index('agent_id');
            }
        });
    }
    public function down(): void {
        Schema::table('properties', function (Blueprint $table) {
            // Only drop currency as other columns existed before this migration
            if (Schema::hasColumn('properties','currency')) {
                $table->dropColumn('currency');
            }
            // Drop the index we added
            if (Schema::hasIndex('properties', ['agent_id'])) {
                $table->dropIndex(['agent_id']);
            }
        });
    }
};
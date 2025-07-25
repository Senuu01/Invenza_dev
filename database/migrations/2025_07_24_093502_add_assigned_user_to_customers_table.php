<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->enum('lead_status', ['lead', 'prospect', 'customer', 'inactive'])->default('lead')->after('assigned_user_id');
            $table->json('tags')->nullable()->after('lead_status'); // For customer segmentation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['assigned_user_id']);
            $table->dropColumn(['assigned_user_id', 'lead_status', 'tags']);
        });
    }
};

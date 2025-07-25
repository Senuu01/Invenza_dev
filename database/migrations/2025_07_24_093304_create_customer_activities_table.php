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
        Schema::create('customer_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Who performed the activity
            $table->string('type'); // 'purchase', 'proposal_sent', 'invoice_created', 'payment_received', 'note_added', etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Store additional data like amounts, product IDs, etc.
            $table->morphs('related'); // For polymorphic relationships (proposals, invoices, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_activities');
    }
};

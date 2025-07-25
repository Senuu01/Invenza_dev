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
        Schema::create('customer_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who created the note
            $table->string('title')->nullable();
            $table->text('content');
            $table->enum('type', ['note', 'call', 'email', 'meeting', 'task'])->default('note');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_private')->default(false);
            $table->timestamp('scheduled_at')->nullable(); // For tasks/follow-ups
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_notes');
    }
};

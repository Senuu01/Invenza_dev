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
        Schema::table('products', function (Blueprint $table) {
            // Add new columns
            $table->string('sku')->unique()->after('id');
            $table->unsignedBigInteger('supplier_id')->nullable()->after('description');
            $table->unsignedBigInteger('category_id')->nullable()->after('supplier_id');
            $table->integer('stock')->default(0)->after('quantity');
            $table->enum('status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock')->after('stock');
            
            // Drop the old category column
            $table->dropColumn('category');
            
            // Add foreign key constraints
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['category_id']);
            
            // Drop new columns
            $table->dropColumn(['sku', 'supplier_id', 'category_id', 'stock', 'status']);
            
            // Re-add the old category column
            $table->string('category')->after('quantity');
        });
    }
};
